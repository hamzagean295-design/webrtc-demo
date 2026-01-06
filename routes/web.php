<?php

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\GoogleController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');



Route::get('/medecin/dashboard', function () {
    $patients = User::where('role', 'patient')->get();
    return view('medecin.dashboard', compact('patients'));
})->name('medecin.dashboard')->middleware('auth');

Route::get('/patient/dashboard', function () {
    $medecins = User::where('role', 'medecin')->get();
    return view('patient.dashboard', compact('medecins'));
})->name('patient.dashboard')->middleware('auth');

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::delete('/logout', function () {
    Auth::logout();
    return to_route('login');
})->name('logout');

Route::get('/register', [GoogleController::class, 'register'])->name('register');
Route::post('/register', [GoogleController::class, 'redirect'])->name('redirect');

Route::get('/login/google/callback', [GoogleController::class, 'callback']);

Route::get('/consultations/{patientId}/{medecinId}', [ConsultationController::class, 'room'])
    ->name('consultation.room')
    ->middleware('auth');

Route::get('/consultations/{userId}', [ConsultationController::class, 'start'])->name('start.consultation');

Route::post('/consultations/signal', [ConsultationController::class, 'signal'])->name('consultation.signal');
