<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\Styluxe\LoginController;
use App\Http\Controllers\Auth\Styluxe\RegisteredUserController;
use App\Http\Controllers\ClothingInventoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/
Route::prefix('styluxe')->group(function () {

    // Public homepage
    Route::get('/', [ClothingInventoryController::class, 'publicHomepage'])->name('styluxe.homepage');
    //Route::get('/items', [ClothingInventoryController::class, 'publicIndex'])->name('styluxe.items.index-public');

    // Unauthorized Page
    Route::get('/unauthorized', function () {
        return view('styluxe.unauthorized');
    })->name('styluxe.unauthorized');

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('styluxe.login');
    Route::post('/login', [LoginController::class, 'login'])->name('styluxe.login.submit');

    // Register
    Route::get('/register', [RegisteredUserController::class, 'showRegisterForm'])->name('styluxe.register');
    Route::post('/register', [RegisteredUserController::class, 'register'])->name('styluxe.register.submit');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('styluxe.logout');
});

Route::middleware(['auth', 'role:admin,manager,staff,supplier'])->prefix('styluxe')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ClothingInventoryController::class, 'dashboard'])->name('styluxe.dashboard');

    // Items (CRUD)
    Route::prefix('items')->group(function () {
        Route::get('/', [ClothingInventoryController::class, 'publicIndex'])->name('styluxe.items.index-public');
        Route::get('/create', [ClothingInventoryController::class, 'create'])->name('styluxe.items.create');
        Route::post('/', [ClothingInventoryController::class, 'store'])->name('styluxe.items.store');
        Route::get('/{barcode}', [ClothingInventoryController::class, 'show'])->name('styluxe.items.show');
        Route::get('/{barcode}/edit', [ClothingInventoryController::class, 'edit'])->name('styluxe.items.edit');
        Route::put('/{barcode}', [ClothingInventoryController::class, 'update'])->name('styluxe.items.update');
        Route::delete('/{barcode}', [ClothingInventoryController::class, 'destroy'])->name('styluxe.items.destroy');
    });
});


Route::get('forgotPassword', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
Route::post('forgotPassword', [ForgotPasswordController::class, 'handleForgotPassword'])->name('password.email');

// require __DIR__.'/auth.php';
