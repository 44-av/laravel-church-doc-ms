<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navbar', function ($view) {
            $notifications = Notification::orderBy('created_at', 'desc')->where('is_read', false)->get();
            $view->with('notifications', $notifications);
            require_once app_path('Helpers/NotificationHelpers.php');
        });
    }
}