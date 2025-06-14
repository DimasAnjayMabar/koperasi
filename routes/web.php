<?php

use App\Http\Controllers\MemberDashboard;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StaffDashboard;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/staff', function () {
    return view('admin_page.welcome');
})->name('staff');

Route::get('/staff/register', function() {
    return view('admin_page.auth.register-page');
})->name('staff-register');

Route::get('/staff/login', function() {
    return view('admin_page.auth.login-page');
})->name('staff-login');

Route::get('/staff/verify-email', function() {
    return view('admin_page.auth.verify-email');
})->name('staff-verify-email');

Route::get('/staff/verify-success', function() {
    return view('admin_page.auth.verify-success');
})->name('staff-verify-success');

Route::post('/register/new-staff', [RegisterController::class, 'registerStaff']);

Route::post('/register/new-member', [RegisterController::class, 'registerMember']);

Route::post('/register/update-staff', [RegisterController::class, 'verifyStaff']);

Route::post('/register/update-member', [RegisterController::class, 'verifyMember']);

Route::get('/staff/find-email', function () {
    return view('admin_page.auth.find-email');
})->name('staff-find-email');

Route::get('/staff/forgot-password', function() {
    return view('admin_page.auth.forgot-password');
});

Route::post('/get-staff', [StaffDashboard::class, 'getStaff']);

Route::post('/get-member', [MemberDashboard::class, 'getMember']);

Route::get('/staff/verify-password', function() {
    return view('admin_page.auth.verify-password');
});

Route::get('/staff/dashboard/history', [StaffDashboard::class,'getHistoryTransaction'])->name('history');

Route::get('/staff/dashboard/simpan', [StaffDashboard::class, 'getMemberAccount'])->name('simpan');

Route::get('/staff/dashboard/profile', function() {
    return view('admin_page.dashboard.profile');
})->name('staff-profile');

Route::post('/resolve-email-staff', [UserController::class, 'loginStaff']);

Route::post('/resolve-email-member', [UserController::class, 'loginMember']);

Route::get('/member/login', function(){
    return view ('user_page.auth.login-page');
})->name('member-login');

Route::get('/member', function(){
    return view('user_page.welcome');
})->name('member');

Route::get('/member/find-email', function(){
    return view('user_page.auth.find-email');
})->name('member-find-email');

Route::get('/member/forgot-password', function(){
    return view('user_page.auth.forgot-password');
});

Route::get('/member/verify-password', function(){
    return view('user_page.auth.verify-password');
});

Route::get('member/dashboard/account', function(){
    return view('user_page.dashboard.account');
})->name('account');

Route::get('member/dashoboard/profile', function(){
    return view('user_page.dashboard.profile');
})->name('member-profile');

Route::get('/member/register', function(){
    return view('user_page.auth.verify-success');
})->name('member-verify-success');

Route::get('/member/new-member', function (){
    return view('user_page.auth.register-page');
})->name('member-register');

Route::get('/member/verify-email', function (){
    return view('user_page.auth.verify-email');
})->name('member-verify-email');

Route::get('preview-member/{id}', [StaffDashboard::class, 'previewMemberAccount']);

Route::post('/edit/staff', [UserController::class, 'editStaff']);
