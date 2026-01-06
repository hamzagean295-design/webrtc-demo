<?php

namespace App\Http\Controllers;

use App\Enums\ConsultationStatus;
use App\Events\DemarrerConsultationEvent;
use App\Events\WebRTCSignal;
use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Media\Audio;

class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::with('aiNote')
            ->where('patient_id', auth()->id())
            ->orWhere('medecin_id', auth()->id())
            ->paginate(40);
        return view('consultations.index', compact('consultations'));
    }

    public function uploadAudio(Request $request, Consultation $consultation)
    {
        $request->validate([
            'audio' => 'required|file|mimes:webm,mp4,wav,mp3',
        ]);

        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            Log::info('Fichier audio reçu pour la consultation #' . $consultation->id, [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);

            $audioPath = $file->getRealPath();
            $audioFile = Audio::fromPath($audioPath);

            $response = Prism::audio()
                ->using(Provider::Groq, 'whisper-large-v3-turbo')
                ->withInput($audioFile)
                ->withProviderOptions([
                    'language' => 'fr', // Indique au modèle de transcrire en français
                ])
                ->asText();

            $notes = $response->text;
            $consultation->aiNote()->updateOrCreate([
                'content' => $notes,
                'validated_by_doctor' => false
            ]);

            return response()->json(['success' => true, 'message' => $response->text]);
        }

        return response()->json(['success' => false, 'message' => 'Aucun fichier audio reçu.'], 400);
    }

    public function start($userId)
    {
        $event = new DemarrerConsultationEvent($userId);
        broadcast($event);
        return redirect()->route('consultation.room', ['patientId' => $userId, 'medecinId' => auth()->id()]);
    }

    public function room($patientId, $medecinId)
    {
        // Find or create a consultation to get a unique ID
        $consultation = Consultation::firstOrCreate(
            [
                'patient_id' => $patientId,
                'medecin_id' => $medecinId,
                'status' => ConsultationStatus::ONGOING,
            ],
            [
                'scheduled_at' => now()
            ]
        );

        return view('consultations.room', [
            'consultation' => $consultation,
            'currentUser' => Auth::user()
        ]);
    }

    public function signal(Request $request)
    {
        $validated = $request->validate([
            'consultationId' => 'required|integer',
            'type' => 'required|string',
            'data' => 'nullable',
        ]);

        // Broadcast the signal to others on the channel
        broadcast(new WebRTCSignal(
            $validated['consultationId'],
            $validated['type'],
            $validated['data']
        ))->toOthers();

        return response()->json(['status' => 'success']);
    }
}
