<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ChatBotNodeController;
use App\Http\Controllers\ChatBotNodeOptionController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageThreadController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageAddOnController;
use App\Http\Controllers\PaymentOptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenuePackageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('landing-page');

Route::middleware(['auth', 'verified', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::post('/store', [BookingController::class, 'store'])->name('store');
        Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('check-availability');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/notifications/{thread}/reply', [NotificationController::class, 'reply'])->name('reply');
        Route::patch('/notifications/{thread}/read', [NotificationController::class, 'markRead'])->name('markRead');
        Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('markAllRead');
        Route::delete('/notifications/{thread}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('destroyAll');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'indexAdmin'])->name('index');
        Route::put('/update/{booking}', [BookingController::class, 'update'])->name('update');
    });

    // Route::get('/time-slots', function () {
    //     return Inertia::render('Admin/TimeSlots');
    // })->name('time-slots');

    Route::prefix('event-types')->name('event-types.')->group(function () {
        Route::get('/', [EventTypeController::class, 'index'])->name('index');
        Route::post('/store', [EventTypeController::class, 'store'])->name('store');
        Route::put('/update/{eventType}', [EventTypeController::class, 'update'])->name('update');
        Route::delete('/destroy/{eventType}', [EventTypeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('venue-packages')->name('venue-packages.')->group(function () {
        Route::get('/', [VenuePackageController::class, 'index'])->name('index');
        Route::post('/store', [VenuePackageController::class, 'store'])->name('store');
        Route::put('/update/{venuePackage}', [VenuePackageController::class, 'update'])->name('update');
        Route::delete('/destroy/{venuePackage}', [VenuePackageController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('package-add-ons')->name('package-add-ons.')->group(function () {
        Route::get('/', [PackageAddOnController::class, 'index'])->name('index');
        Route::post('/store', [PackageAddOnController::class, 'store'])->name('store');
        Route::put('/update/{packageAddOn}', [PackageAddOnController::class, 'update'])->name('update');
        Route::delete('/destroy/{packageAddOn}', [PackageAddOnController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('payment-options')->name('payment-options.')->group(function () {
        Route::get('/', [PaymentOptionController::class, 'index'])->name('index');
        Route::put('/update/{paymentOption}', [PaymentOptionController::class, 'update'])->name('update');
    });

    Route::prefix('/messages')->name('messages.')->group(function () {
        Route::get('/', [MessageThreadController::class, 'index'])->name('index');
        Route::post('/store', [MessageThreadController::class, 'store'])->name('store');
        Route::post('/{thread}/reply', [MessageThreadController::class, 'reply'])->name('reply');
        Route::patch('/{thread}/read', [MessageThreadController::class, 'markRead'])->name('markRead');
        Route::delete('/{thread}', [MessageThreadController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::put('/update-status/{user}', [UserController::class, 'updateStatus'])->name('update-status');
    });

    Route::prefix('chat-nodes')->name('chat-nodes.')->group(function () {
        Route::get('/', [ChatBotNodeController::class, 'index'])->name('index');
        Route::post('/store', [ChatBotNodeController::class, 'store'])->name('store');
        Route::put('/update/{chatBotNode}', [ChatBotNodeController::class, 'update'])->name('update');
        Route::delete('/destroy/{chatBotNode}', [ChatBotNodeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('chat-node-options')->name('chat-node-options.')->group(function () {
        Route::get('/', [ChatBotNodeOptionController::class, 'index'])->name('index');
        Route::post('/store', [ChatBotNodeOptionController::class, 'store'])->name('store');
        Route::put('/update/{nodeId}', [ChatBotNodeOptionController::class, 'update'])->name('update');
        Route::delete('/destroy/{nodeId}', [ChatBotNodeOptionController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', function () {
        return Inertia::render('Admin/Profile');
    })->name('profile');
});

Route::middleware('auth')->group(function () {
    Route::put('/update-information', [ProfileController::class, 'updateInformation'])->name('update-information');
    Route::put('/update-credentials', [ProfileController::class, 'updateCredentials'])->name('update-credentials');

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/api/chatbot', [ChatBotController::class, 'index'])->name('chatbot.index');
Route::get('/api/chatbot/nodes', [ChatBotController::class, 'nodes'])->name('chatbot.nodes');

require __DIR__ . '/auth.php';
