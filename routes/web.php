<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\{DashboardController, ReservationController};
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    UserManagementController,
    ParkingSpotController,
    ReservationManagementController
};

// === PUBLIEKE ROUTES ===
Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// === USER ROUTES (ingelogd + niet gebanned) ===
Route::middleware(['auth', 'banned'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reserveringen
    Route::get('/reserveren', [ReservationController::class, 'create'])->name('reserve');
    Route::post('/reserveren/betaal', [ReservationController::class, 'betaalForm'])->name('betaal');
    Route::post('/reserveren/opslaan', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reserveringen', [ReservationController::class, 'index'])->name('reservations');
    Route::delete('/reserveringen/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// === ADMIN ROUTES ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/gebruikers', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/gebruikers/{user}/bewerken', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/gebruikers/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::post('/gebruikers/{user}/ban', [UserManagementController::class, 'ban'])->name('users.ban');
    Route::delete('/gebruikers/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Parkeerplaatsen
    Route::resource('parkeerplaatsen', ParkingSpotController::class)->names([
        'index'   => 'spots.index',
        'create'  => 'spots.create',
        'store'   => 'spots.store',
        'edit'    => 'spots.edit',
        'update'  => 'spots.update',
        'destroy' => 'spots.destroy',
    ]);

    // Reserveringen
    Route::get('/reserveringen', [ReservationManagementController::class, 'index'])->name('reservations.index');
    Route::put('/reserveringen/{reservation}', [ReservationManagementController::class, 'update'])->name('reservations.update');
    Route::delete('/reserveringen/{reservation}', [ReservationManagementController::class, 'destroy'])->name('reservations.destroy');
});
