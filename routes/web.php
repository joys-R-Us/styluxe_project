<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\Styluxe\LoginController;
use App\Http\Controllers\Auth\Styluxe\RegisteredUserController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
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

// ===== PUBLIC ROUTES =====
Route::prefix('styluxe')->group(function () {
    
    // Public homepage
    Route::get('/', [HomeController::class, 'index'])->name('styluxe.homepage');

    // View all items (all roles)
    Route::get('/items', [ProductsController::class, 'publicIndex'])
        ->name('styluxe.items.index-public');

    // Authentication
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('styluxe.login');
        Route::post('/login', [LoginController::class, 'login'])->name('styluxe.login.submit');
        
        Route::get('/register', [RegisteredUserController::class, 'showRegisterForm'])->name('styluxe.register');
        Route::post('/register', [RegisteredUserController::class, 'register'])->name('styluxe.register.submit');
    });

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware(\App\Http\Middleware\EnsureStyluxeAuthenticated::class)
        ->name('styluxe.logout');

    // Unauthorized page
    Route::get('/unauthorized', function () {
        return view('styluxe.unauthorized');
    })->name('styluxe.unauthorized');
});


// ===== AUTHENTICATED ROUTES =====
// Use EnsureStyluxeAuthenticated middleware to show the unauthorized view when not logged in
Route::middleware([\App\Http\Middleware\EnsureStyluxeAuthenticated::class])->prefix('styluxe')->group(function () {
    
    // ===== DASHBOARD =====
    Route::get('/dashboard', [AnalyticsController::class, 'index'])->name('styluxe.dashboard');

    // ===== ITEMS/PRODUCTS =====
    Route::prefix('items')->group(function () {
        // Create/Edit/Delete (Admin only)
        Route::middleware('role:admin')->group(function () {
            // move admin index to /manage to avoid overriding the public items route
            Route::get('/manage', [ProductsController::class, 'index'])->name('styluxe.items.index');
            Route::get('/create', [ProductsController::class, 'create'])->name('styluxe.items.create');
            Route::post('/', [ProductsController::class, 'store'])->name('styluxe.items.store');
            Route::get('/{barcode}/edit', [ProductsController::class, 'edit'])->name('styluxe.items.edit');
            Route::put('/{barcode}', [ProductsController::class, 'update'])->name('styluxe.items.update');
        });

        // View single item (all roles)
        Route::get('/{barcode}', [ProductsController::class, 'show'])->name('styluxe.items.show');

        // Delete (Admin only)
        Route::delete('/{barcode}', [ProductsController::class, 'destroy'])->middleware('role:admin')->name('styluxe.items.destroy');
    });


    // ===== ORDERS (Clients) =====
    Route::middleware('role:client')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('styluxe.orders.index');
        Route::get('/create', [OrderController::class, 'create'])->name('styluxe.orders.create');
        Route::post('/', [OrderController::class, 'store'])->name('styluxe.orders.store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('styluxe.orders.show');
    });

    // ===== ORDER MANAGEMENT (Admin only) =====
    Route::middleware('role:admin')->prefix('order-management')->group(function () {
        Route::get('/', [OrderController::class, 'adminIndex'])->name('styluxe.order-management.index');
        Route::get('/{id}', [OrderController::class, 'adminShow'])->name('styluxe.order-management.show');
        Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('styluxe.order-management.update-status');
    });


    // ===== NOTIFICATIONS =====
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('styluxe.notifications.index');
        Route::match(['get', 'post'], '/{id}/read', [NotificationController::class, 'markAsRead'])->name('styluxe.notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('styluxe.notifications.read-all');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->middleware(\App\Http\Middleware\EnsureStyluxeAuthenticated::class)->name('styluxe.notifications.unread-count');
    });

    // ===== SETTINGS =====
    Route::prefix('settings')->group(function () {
        // Profile settings (all roles)
        Route::get('/profile', [SettingsController::class, 'profile'])->name('styluxe.settings.profile');
        Route::patch('/profile', [SettingsController::class, 'updateProfile'])->name('styluxe.settings.profile.update');
        
        // User management (Admin only)
        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [SettingsController::class, 'userManagement'])->name('styluxe.settings.users');
            Route::get('/users/create', [SettingsController::class, 'createUser'])->name('styluxe.settings.users.create');
            Route::post('/users', [SettingsController::class, 'storeUser'])->name('styluxe.settings.users.store');
            Route::post('/users/{id}/toggle', [SettingsController::class, 'toggleUserStatus'])->name('styluxe.settings.toggle-status');
            Route::delete('/users/{id}', [SettingsController::class, 'deleteUser'])->name('styluxe.settings.users.delete');
        });
    });

    // ===== ADMIN MANAGEMENT =====
    Route::middleware('role:admin')->group(function () {
        // Batch upload items
        Route::get('/batch-upload', [ProductsController::class, 'showBatchUploadForm'])->name('styluxe.batch-upload');
        Route::post('/batch-upload', [ProductsController::class, 'batchUpload'])->name('styluxe.batch-upload.store');
    });
});


// ===== API ROUTES (Optional for future mobile app) =====
Route::prefix('api/styluxe')->middleware('auth:sanctum')->group(function () {
    // Items
    Route::get('/items', [ProductsController::class, 'apiIndex']);
    Route::get('/items/{barcode}', [ProductsController::class, 'apiShow']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'apiIndex']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'apiIndex']);
});

//Route::get('forgotPassword', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
//Route::post('forgotPassword', [ForgotPasswordController::class, 'handleForgotPassword'])->name('password.email');

// require __DIR__.'/auth.php';
