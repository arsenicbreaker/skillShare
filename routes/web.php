<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SwapRequestController;
use App\Http\Controllers\SkillController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// Auth
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Onboarding (harus login tapi belum onboarding)
    Route::get('/onboarding/step1', [OnboardingController::class, 'step1'])->name('onboarding.step1');
    Route::post('/onboarding/step1', [OnboardingController::class, 'step1Save'])->name('onboarding.step1.save');
    Route::get('/onboarding/step2', [OnboardingController::class, 'step2'])->name('onboarding.step2');
    Route::post('/onboarding/step2', [OnboardingController::class, 'step2Save'])->name('onboarding.step2.save');

    // Harus sudah onboarding
    Route::middleware(['onboarded'])->group(function () {

        // Dashboard/Discover
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profil
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

        // Swap Request
        Route::get('/requests', [SwapRequestController::class, 'index'])->name('swap.index');
        Route::post('/requests/send', [SwapRequestController::class, 'send'])->name('swap.send');
        Route::post('/requests/{id}/accept', [SwapRequestController::class, 'accept'])->name('swap.accept');
        Route::post('/requests/{id}/reject', [SwapRequestController::class, 'reject'])->name('swap.reject');
        Route::post('/requests/{id}/cancel', [SwapRequestController::class, 'cancel'])->name('swap.cancel');

        // Skill
        Route::get('/skills', [SkillController::class, 'index'])->name('skill.index');
        Route::get('/skills/search', [SkillController::class, 'search'])->name('skill.search');
    });
});