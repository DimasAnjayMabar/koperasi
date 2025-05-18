<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
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
    return view('admin.navbar');
})->name('dashboard');

Route::post('/register/update-user', [RegisterController::class, 'checkUserExist']);

Route::get('/find-email', function () {
    return view('auth.find-email');
})->name('find-email');

Route::get('/reset-password', function() {
    return view('auth.forgot-password');
});

Route::post('/get-user', [UserController::class, 'getInfo']);

Route::get('/verify-password', function() {
    return view('auth.verify-password');
});

Route::get('/dashboard/simpan', function() {
    return view('admin.simpan');
});

Route::get('/dashboard/pinjam', function() {
    return view('admin.pinjam');
});
