<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // WebPush subscription routes
    Route::post('/push-subscriptions', 'App\Http\Controllers\PushSubscriptionController@store')->name('push-subscriptions.store');
    Route::delete('/push-subscriptions', 'App\Http\Controllers\PushSubscriptionController@destroy')->name('push-subscriptions.destroy');
});

Route::get('/js/enable-push.js', function () {
    return response()->view('js.enable-push')->header('Content-Type', 'application/javascript');
});

// Add this route for testing
Route::get('/test-notification', function () {
    $user = auth()->user();
    $order = \App\Models\Order::first(); // Get the first order or create one if needed

    if (!$user) {
        return 'You must be logged in to test notifications.';
    }

    if (!$order) {
        return 'No orders found in the database. Please create an order first.';
    }

    try {
        $user->notify(new \App\Notifications\OrderStatusUpdated($order));
        return 'Notification sent successfully!';
    } catch (\Exception $e) {
        return 'Error sending notification: ' . $e->getMessage();
    }
})->middleware('auth');

// Add this route for debugging
Route::get('/check-subscriptions', function () {
    $user = auth()->user();
    if ($user) {
        $subscriptions = $user->pushSubscriptions;
        return 'You have ' . $subscriptions->count() . ' push subscriptions.';
    }
    return 'Not logged in.';
})->middleware('auth');

require __DIR__ . '/auth.php';
