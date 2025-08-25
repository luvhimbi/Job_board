<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/jobs/create', [JobPostingController::class, 'dashboard'])->name('jobs.create');
    Route::post('/jobs', [JobPostingController::class, 'store'])->name('jobs.store');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/resume', [ProfileController::class, 'showResume'])->name('profile.resume');

    Route::get('/employer/dashboard', [JobPostingController::class, 'index'])->name('employer.dashboard');

    Route::get('/applicant/dashboard', [JobPostingController::class, 'applicantDashboard'])->name('applicant.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/jobs/{job}/apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applicants', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.my_applications');
});
