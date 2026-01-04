<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');



Route::get('/medecin/dashboard', function () {
    return view('medecin.dashboard');
})->name('medecin.dashboard')->middleware('auth');

Route::get('/patient/dashboard', function () {
    return view('patient.dashboard');
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
