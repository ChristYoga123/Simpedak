<?php

use App\Http\Controllers\Owner\Auth\LoginController;
use App\Http\Controllers\Owner\CooperateController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\RawProductController;
use App\Http\Controllers\Owner\ServeProductController;
use App\Http\Controllers\Owner\TransactionController;
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
    // Raw Product
    Route::get("produk/bahan-baku", [RawProductController::class, "index"])->name("owner.product.raw-product.index");
    Route::post("produk/bahan-baku", [RawProductController::class, "store"])->name("owner.product.raw-product.store");
    Route::get("produk/bahan-baku/{productOwner}/show", [RawProductController::class, "show"])->name("owner.product.raw-product.show");
    Route::put("produk/bahan-baku/{productOwner}/update", [RawProductController::class, "update"])->name("owner.product.raw-product.update");
    Route::put("produk/bahan-baku/kuantitas/{productOwner}/update", [RawProductController::class, "updateQuantity"])->name("owner.product.raw-product.updateQuantity");
    Route::get("produk/bahan-baku/riwayat/{slug}", [RawProductController::class, "index_history"])->name("owner.product.raw-product.history");
    // Serve Product
    Route::get("produk/produk-jadi", [ServeProductController::class, "index"])->name("owner.product.serve-product.index");
    Route::get("produk/produk-jadi/{productOwner}/show", [ServeProductController::class, "show"])->name("owner.product.serve-product.show");
    Route::post("produk/produk-jadi", [ServeProductController::class, "store"])->name("owner.product.serve-product.store");
    Route::put("produk/produk-jadi/{productOwner}/update", [ServeProductController::class, "update"])->name("owner.product.serve-product.update");
    Route::put("produk/produk-jadi/kuantitas/{productOwner}/update", [ServeProductController::class, "updateQuantity"])->name("owner.product.serve-product.updateQuantity");
    // Transaction
    Route::get("transaksi", [TransactionController::class, "index"])->name("owner.transaction.index");
    Route::get("transaksi/{transaction}/show", [TransactionController::class, "show"])->name("owner.transaction.show");
    Route::post("transaksi", [TransactionController::class, "store"])->name("owner.transaction.store");
    Route::put("transaksi/{transaction}/update", [TransactionController::class, "update"])->name("owner.transaction.update");
    // Integration
    Route::get("integrasi", [CooperateController::class, "index"])->name("owner.integration.index");
    Route::get("integrasi/supplier/{user}", [CooperateController::class, "showSupplier"])->name("owner.integration.showSupplier");
    Route::post("integrasi/{supplier_id}", [CooperateController::class, "store"])->name("owner.integration.store");
});


require __DIR__ . '/auth.php';
