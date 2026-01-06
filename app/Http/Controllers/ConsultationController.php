<?php

namespace App\Http\Controllers;

use App\Enums\ConsultationStatus;
use App\Events\DemarrerConsultationEvent;
use App\Events\WebRTCSignal;
use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
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
                'date' => now()
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
