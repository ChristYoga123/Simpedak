<?php

use App\Http\Controllers\Owner\Auth\LoginController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix("admin")->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* Owner Route */
// Non Auth
Route::prefix("owner")->middleware("guest_owner")->group(function () {
    // Login
    Route::get("login", [LoginController::class, "index"])->name("owner.login.index");
    Route::post("login", [LoginController::class, "login"])->name("owner.login");
});

// Auth
Route::prefix("owner")->middleware("auth_owner")->group(function () {
    // Login
    Route::post("logout", [LoginController::class, "logout"])->name("owner.logout");
    // Dashboard
    Route::get("dashboard", [DashboardController::class, "index"])->name("owner.dashboard.index");
});


require __DIR__ . '/auth.php';
