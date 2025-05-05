<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::get('/register', function() {
    return view('auth.register-page');
})->name('register');

Route::get('/login', function() {
    return view('auth.login-page');
})->name('login');

Route::get('/verify-email', function() {
    return view('auth.verify-email');
})->name('verify-email');

Route::get('/verify-success', function() {
    return view('auth.verify-success');
})->name('verify-success');

Route::post('/register/new-user', [RegisterController::class, 'register']);

Route::get('/dashboard', function() {
    return view('admin.dashboard');
})->name('dashboard');