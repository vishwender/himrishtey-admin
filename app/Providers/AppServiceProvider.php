<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\AdminActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;

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
        Paginator::useBootstrapFive();
        /*
        |--------------------------------------------------------------------------
        | Admin Login Activity
        |--------------------------------------------------------------------------
        */

        Event::listen(Login::class, function (Login $event) {

            if ($event->guard !== 'admin') {
                return;
            }

            AdminActivityLog::create([
                'admin_id' => $event->user->id,
                'site_id' => session('admin_site_id'),
                'action' => 'login',
                'module' => 'authentication',
                'description' => 'Staff logged into the admin panel.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });


        /*
    |--------------------------------------------------------------------------
    | Admin Logout Activity
    |--------------------------------------------------------------------------
    */

        Event::listen(Logout::class, function (Logout $event) {

            if ($event->guard !== 'admin') {
                return;
            }

            AdminActivityLog::create([
                'admin_id' => $event->user?->id,
                'site_id' => session('admin_site_id'),
                'action' => 'logout',
                'module' => 'authentication',
                'description' => 'Staff logged out of the admin panel.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}
