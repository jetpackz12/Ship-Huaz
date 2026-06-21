<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update-information', [ProfileController::class, 'updateInformation'])->name('update-information');
        Route::put('/update-credentials', [ProfileController::class, 'updateCredentials'])->name('update-credentials');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/bookings', function () {
        return Inertia::render('Admin/Bookings');
    })->name('bookings');

    Route::get('/time-slots', function () {
        return Inertia::render('Admin/TimeSlots');
    })->name('time-slots');

    Route::get('/event-types', function () {
        return Inertia::render('Admin/EventTypes');
    })->name('event-types');

    Route::get('/venue-packages', function () {
        return Inertia::render('Admin/VenuePackages');
    })->name('venue-packages');

    Route::get('/package-add-ons', function () {
        return Inertia::render('Admin/PackageAddOns');
    })->name('package-add-ons');

    Route::get('/payment-options', function () {
        return Inertia::render('Admin/PaymentOptions');
    })->name('payment-options');

    Route::get('/messages', function () {
        return Inertia::render('Admin/Messages');
    })->name('messages');

    Route::get('/clients', function () {
        return Inertia::render('Admin/Clients');
    })->name('clients');

    Route::get('/chat-nodes', function () {
        return Inertia::render('Admin/ChatNodes');
    })->name('chat-nodes');

    Route::get('/chat-node-options', function () {
        return Inertia::render('Admin/ChatNodeOptions');
    })->name('chat-node-options');

    Route::get('/profile', function () {
        return Inertia::render('Admin/Profile');
    })->name('profile');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
