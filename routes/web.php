<?php

use App\Http\Controllers\Home\RegisterController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Owner\Auth\LoginController as OwnerLoginController;
use App\Http\Controllers\Owner\CooperateController as OwnerCooperateController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\Owner\RawProductController;
use App\Http\Controllers\Owner\ScheduleController;
use App\Http\Controllers\Owner\ServeProductController;
use App\Http\Controllers\Owner\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supplier\Auth\LoginController as SupplierLoginController;
use App\Http\Controllers\Supplier\CooperateController as SupplierCooperateController;
use App\Http\Controllers\Supplier\DashboardController as SupplierDashboardController;
use App\Http\Controllers\Supplier\ProfileController as SupplierProfileController;
use Illuminate\Console\Scheduling\Schedule;
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

Route::get('/', [HomeController::class, "index"])->name("home.index");
Route::get("/detail-fitur", [HomeController::class, "feature"])->name("home.feature");
Route::get("/register", [RegisterController::class, "register_index"])->name("home.register");
Route::post("/register", [RegisterController::class, "register"])->name("home.register");
Route::post("/login", [RegisterController::class, "login"])->name("home.login");
// Midtrans
Route::get("payment/success", [RegisterController::class, "midtransCallback"]);
Route::post("payment/success", [RegisterController::class, "midtransCallback"]);

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix("admin")->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User
    Route::put("users/{user}", [UserController::class, "update"])->name("user.update");
});

/* Owner Route */
// Non Auth
Route::prefix("owner")->middleware("guest_owner")->name("owner.")->group(function () {
    // Login
    Route::get("login", [OwnerLoginController::class, "index"])->name("login.index");
    Route::post("login", [OwnerLoginController::class, "login"])->name("login");
});

// Auth
Route::prefix("owner")->middleware(["auth_owner", "role:Owner"])->name("owner.")->group(function () {
    // Login
    Route::post("logout", [OwnerLoginController::class, "logout"])->name("logout");
    // Dashboard
    Route::get("/", [DashboardController::class, "index"])->name("dashboard.index");
    // Raw Product
    Route::get("produk/bahan-baku", [RawProductController::class, "index"])->name("product.raw-product.index");
    Route::post("produk/bahan-baku", [RawProductController::class, "store"])->name("product.raw-product.store");
    Route::get("produk/bahan-baku/{productOwner}/show", [RawProductController::class, "show"])->name("product.raw-product.show");
    Route::put("produk/bahan-baku/{productOwner}/update", [RawProductController::class, "update"])->name("product.raw-product.update");
    Route::put("produk/bahan-baku/kuantitas/{productOwner}/update", [RawProductController::class, "updateQuantity"])->name("product.raw-product.updateQuantity");
    Route::get("produk/bahan-baku/riwayat/{slug}", [RawProductController::class, "index_history"])->name("product.raw-product.history");
    // Serve Product
    Route::get("produk/produk-jadi", [ServeProductController::class, "index"])->name("product.serve-product.index");
    Route::get("produk/produk-jadi/{productOwner}/show", [ServeProductController::class, "show"])->name("product.serve-product.show");
    Route::post("produk/produk-jadi", [ServeProductController::class, "store"])->name("product.serve-product.store");
    Route::put("produk/produk-jadi/{productOwner}/update", [ServeProductController::class, "update"])->name("product.serve-product.update");
    Route::put("produk/produk-jadi/kuantitas/{productOwner}/update", [ServeProductController::class, "updateQuantity"])->name("product.serve-product.updateQuantity");
    // Transaction
    Route::get("transaksi", [TransactionController::class, "index"])->name("transaction.index");
    Route::get("transaksi/{transaction}/show", [TransactionController::class, "show"])->name("transaction.show");
    Route::post("transaksi", [TransactionController::class, "store"])->name("transaction.store");
    Route::put("transaksi/{transaction}/update", [TransactionController::class, "update"])->name("transaction.update");
    // Integration
    Route::get("integrasi", [OwnerCooperateController::class, "index"])->name("integration.index");
    Route::get("integrasi/supplier/{user}", [OwnerCooperateController::class, "showSupplier"])->name("integration.showSupplier");
    Route::post("integrasi/{supplier_id}", [OwnerCooperateController::class, "store"])->name("integration.store");
    // Schedule
    Route::get("jadwal", [ScheduleController::class, "index"])->name("jadwal.index");
    Route::get("jadwal/{animal_id}/show", [ScheduleController::class, "show"])->name("jadwal.show");
    Route::post("jadwal", [ScheduleController::class, "store"])->name("jadwal.store");
    Route::put("jadwal/{animalOwner}/update", [ScheduleController::class, "update"])->name("jadwal.update");
    // Profile
    Route::get("profile", [OwnerProfileController::class, "index"])->name("profile.index");
    Route::put("profile/{user}", [OwnerProfileController::class, "update"])->name("profile.update");
});

/* Supplier Route */
// Non Auth
Route::prefix("supplier")->name("supplier.")->middleware("guest_supplier")->group(function () {
    Route::get("login", [SupplierLoginController::class, "index"])->name("login.index");
    Route::post("login", [SupplierLoginController::class, "login"])->name("login");
});

// Auth
Route::prefix("supplier")->name("supplier.")->middleware(["auth_supplier", "role:Supplier"])->group(function () {
    // Dashboard
    Route::get("/", [SupplierDashboardController::class, "index"])->name("dashboard.index");
    // Login
    Route::post("logout", [SupplierLoginController::class, "logout"])->name("logout");
    // Integration
    Route::get("integrasi", [SupplierCooperateController::class, "index"])->name("integration.index");
    Route::get("integrasi/owner/{user}", [SupplierCooperateController::class, "showOwner"])->name("integration.showOwner");
    Route::post("integrasi/owner/{user}", [SupplierCooperateController::class, "updateCooperateSchedule"])->name("integration.store");
    Route::post("integrasi/owner/final/{user}", [SupplierCooperateController::class, "updateCooperate"])->name("integration.cooperate.final.update");
    // Profile
    Route::get("profile", [SupplierProfileController::class, "index"])->name("profile.index");
    Route::put("profile/{user}", [SupplierProfileController::class, "update"])->name("profile.update");
});


require __DIR__ . '/auth.php';
