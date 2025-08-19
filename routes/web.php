<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Dashboard\AttendanceList;
use App\Livewire\Dashboard\ProjectList;
use App\Livewire\Dashboard\UserDashboard;
use App\Livewire\Employees\EmployeeList;
use App\Livewire\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return $user->role === 'admin'
        ? redirect()->route('dashboard.admin')
        : redirect()->route('dashboard.user');
    })->name('dashboard');

    Route::get('/dashboard/admin', AdminDashboard::class)
        ->middleware('admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/user', UserDashboard::class)
        ->name('dashboard.user');
    Route::get('/employees', EmployeeList::class)->name('employees');
    Route::get('/projects', ProjectList::class)->name('projects');
    Route::get('/attendance', AttendanceList::class)->name('attendance.list');
    Route::get('/profile', Profile::class)->name('profile');
});
