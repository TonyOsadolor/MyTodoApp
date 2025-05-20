<?php

use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use Illuminate\Support\Facades\Auth;
use App\Livewire\DashboardComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\Settings\Appearance;
use App\Livewire\Tasks\TaskComponent;
use App\Livewire\Tasks\EditTaskComponent;
use App\Livewire\Tasks\ShowTaskComponent;
use App\Livewire\Subscriptions\SubscriptionComponent;
use App\Livewire\Notifications\NotificationComponent;
use App\Livewire\Subscriptions\ShowSubscriptionComponent;
use App\Livewire\Notifications\ShowNotificationComponent;

Route::get('/', function () {

    if (Auth::user()) {
        return redirect('/dashboard');
    }

    return redirect('/login');

    // return view('welcome');
})->name('home');

Route::prefix('/cronjobs')->group(function () {
    Route::get('/send-today-notification', function () {
        return response()->json('Notifications Scheduled', 200);
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');

    Route::prefix('/tasks')->group(function () {
        Route::get('/', TaskComponent::class)->name('tasks');
        Route::prefix('/{task:uuid}')->group(function () {
            Route::get('/', ShowTaskComponent::class);
            Route::get('/edit', EditTaskComponent::class);
        });
    });

    Route::prefix('/notifications')->group(function () {
        Route::get('/', NotificationComponent::class)->name('notifications');
        Route::prefix('/{id}')->group(function () {
            Route::get('/', ShowNotificationComponent::class);
        });
    });

    Route::prefix('/subscriptions')->group(function () {
        Route::get('/', SubscriptionComponent::class)->name('subscriptions');
        Route::prefix('/{id}')->group(function () {
            Route::get('/', ShowSubscriptionComponent::class);
        });
    });

});

// Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
