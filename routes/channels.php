<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// This rule authorizes a user to listen on their own private patient channel.
Broadcast::channel('user.{patientId}', function ($user, $patientId) {
    return (int) $user->id === (int) $patientId;
});


Broadcast::channel('consultation.{consultationId}', function ($user, $consultationId) {
    Log::info("Authorizing user {$user->id} for consultation channel: {$consultationId}");

    // --- Security Check ---
    // In a real app, you MUST verify the user belongs to this consultation.
    $consultation = \App\Models\Consultation::find($consultationId);
    if ($consultation && ($consultation->patient_id == $user->id || $consultation->medecin_id == $user->id)) {
        // If authorized, return the user's data for the presence channel.
        return ['id' => $user->id, 'name' => $user->name];
    }

    // If not authorized, return null.
    return null;
});
