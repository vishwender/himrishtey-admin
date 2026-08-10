<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberActivityController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Guest
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin.guest')->group(function () {

        Route::get('/login', [
            AuthController::class,
            'showLogin'
        ])->name('login');

        Route::post('/login', [
            AuthController::class,
            'login'
        ])->name('login.submit');
    });


    /*
    |--------------------------------------------------------------------------
    | Authenticated Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin.auth')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Route::post('/logout', [
            AuthController::class,
            'logout',
        ])->name('logout');


        /*
        |--------------------------------------------------------------------------
        | Site Selection
        |--------------------------------------------------------------------------
        */

        Route::get('/site/select', function () {

            return view('admin.sites.select', [
                'sites' => auth('admin')->user()
                    ->sites()
                    ->where('status', true)
                    ->get(),
            ]);
        })->name('site.select');


        Route::post('/site/{site}/switch', [
            SiteController::class,
            'switch',
        ])->name('site.switch');


        /*
        |--------------------------------------------------------------------------
        | Site-specific Admin
        |--------------------------------------------------------------------------
        |
        | Every route inside this group uses the selected site's
        | database connection.
        |
        */

        Route::middleware('admin.site')->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get('/dashboard', [
                DashboardController::class,
                'index',
            ])->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | Members
            |--------------------------------------------------------------------------
            */

            Route::get('/members', [
                MemberController::class,
                'index',
            ])->name('members.index');


            Route::get('/members/{id}', [
                MemberController::class,
                'show',
            ])->name('members.show');


            /*
            |--------------------------------------------------------------------------
            | Member Toggle Actions
            |--------------------------------------------------------------------------
            */

            Route::post('/members/{id}/toggle-status', [
                MemberController::class,
                'toggleStatus',
            ])->name('members.toggle-status');


            Route::post('/members/{id}/toggle-trusted', [
                MemberController::class,
                'toggleTrusted',
            ])->name('members.toggle-trusted');


            Route::post('/members/{id}/toggle-visibility', [
                MemberController::class,
                'toggleVisibility',
            ])->name('members.toggle-visibility');


            Route::post('/members/{id}/toggle-promoted', [
                MemberController::class,
                'togglePromoted',
            ])->name('members.toggle-promoted');

            /*
            |--------------------------------------------------------------------------
            | Member Activity
            |--------------------------------------------------------------------------
            */

            Route::get('/activities', [
                MemberActivityController::class,
                'index',
            ])->name('activities.index');


            Route::get('/activities/search-members', [
                MemberActivityController::class,
                'searchMembers',
            ])->name('activities.search-members');


            Route::get('/activities/member/{memberId}', [
                MemberActivityController::class,
                'memberActivity',
            ])->name('activities.member');

            Route::post('/members/{memberId}/photos/{photoId}/set-profile', [
                MemberController::class,
                'setProfilePhoto',
            ])->name('members.photos.set-profile');

            Route::post('/members/{memberId}/photos/{photoId}/approve', [
                MemberController::class,
                'approvePhoto',
            ])->name('members.photos.approve');

            Route::post('/members/{memberId}/photos/{photoId}/reject', [
                MemberController::class,
                'rejectPhoto',
            ])->name('members.photos.reject');

            Route::delete('/members/{memberId}/photos/{photoId}', [
                MemberController::class,
                'deletePhoto',
            ])->name('members.photos.delete');
        });
    });
});
