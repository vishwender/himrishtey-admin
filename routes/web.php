<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberActivityController;
use App\Http\Controllers\Admin\MemberPhotoController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\CastController;
use App\Http\Controllers\Admin\ReligionController;
use App\Http\Controllers\Admin\OccupationController;
use App\Http\Controllers\Admin\EmployerController;
use App\Http\Controllers\Admin\AnnualIncomeController;
use App\Http\Controllers\Admin\FamilyStatusController;
use App\Http\Controllers\Admin\MaritalStatusController;
use App\Http\Controllers\Admin\MotherTongueController;
use App\Http\Controllers\Admin\HeightController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CityController;

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

            Route::get('/members/{id}/edit', [
                MemberController::class,
                'edit',
            ])->name('members.edit');

            Route::put('/members/{id}', [
                MemberController::class,
                'update',
            ])->name('members.update');

            /*
    |--------------------------------------------------------------------------
    | Member Photo Management
    |--------------------------------------------------------------------------
    */

            Route::post(
                '/members/{memberId}/photos',
                [MemberPhotoController::class, 'store']
            )->name('members.photos.store');


            Route::post(
                '/members/{memberId}/photos/{photoId}/set-profile',
                [MemberPhotoController::class, 'setProfile']
            )->name('members.photos.set-profile');


            Route::post(
                '/members/{memberId}/photos/{photoId}/approve',
                [MemberPhotoController::class, 'approve']
            )->name('members.photos.approve');


            Route::post(
                '/members/{memberId}/photos/{photoId}/unapprove',
                [MemberPhotoController::class, 'unapprove']
            )->name('members.photos.unapprove');


            Route::delete(
                '/members/{memberId}/photos/{photoId}',
                [MemberPhotoController::class, 'destroy']
            )->name('members.photos.destroy');

            Route::post(
                '/members/{memberId}/membership/change',
                [MemberController::class, 'changeMembership']
            )->name('members.membership.change');

            Route::post(
                '/members/{memberId}/relationship-manager',
                [MemberController::class, 'updateRelationshipManager']
            )->name('members.relationship-manager.update');

            Route::post(
                '/members/{memberId}/remarks',
                [MemberController::class, 'updateRemarks']
            )->name('members.remarks.update');

            Route::get(
                '/educations',
                [EducationController::class, 'index']
            )->name('educations.index');


            Route::post(
                '/educations',
                [EducationController::class, 'store']
            )->name('educations.store');


            Route::put(
                '/educations/{id}',
                [EducationController::class, 'update']
            )->name('educations.update');


            Route::delete(
                '/educations/{id}',
                [EducationController::class, 'destroy']
            )->name('educations.destroy');

            Route::get(
                '/casts',
                [CastController::class, 'index']
            )->name('casts.index');

            Route::post(
                '/casts',
                [CastController::class, 'store']
            )->name('casts.store');

            Route::put(
                '/casts/{id}',
                [CastController::class, 'update']
            )->name('casts.update');

            Route::delete(
                '/casts/{id}',
                [CastController::class, 'destroy']
            )->name('casts.destroy');

            Route::get(
                '/religions',
                [ReligionController::class, 'index']
            )->name('religions.index');

            Route::post(
                '/religions',
                [ReligionController::class, 'store']
            )->name('religions.store');

            Route::put(
                '/religions/{id}',
                [ReligionController::class, 'update']
            )->name('religions.update');

            Route::delete(
                '/religions/{id}',
                [ReligionController::class, 'destroy']
            )->name('religions.destroy');

            Route::get(
                '/occupations',
                [OccupationController::class, 'index']
            )->name('occupations.index');

            Route::post(
                '/occupations',
                [OccupationController::class, 'store']
            )->name('occupations.store');

            Route::put(
                '/occupations/{id}',
                [OccupationController::class, 'update']
            )->name('occupations.update');

            Route::patch(
                '/occupations/{id}/toggle-status',
                [OccupationController::class, 'toggleStatus']
            )->name('occupations.toggle-status');

            Route::get(
                '/employers',
                [EmployerController::class, 'index']
            )->name('employers.index');

            Route::post(
                '/employers',
                [EmployerController::class, 'store']
            )->name('employers.store');

            Route::put(
                '/employers/{id}',
                [EmployerController::class, 'update']
            )->name('employers.update');

            Route::delete(
                '/employers/{id}',
                [EmployerController::class, 'destroy']
            )->name('employers.destroy');

            Route::get(
                '/annual-incomes',
                [AnnualIncomeController::class, 'index']
            )->name('annual-incomes.index');

            Route::post(
                '/annual-incomes',
                [AnnualIncomeController::class, 'store']
            )->name('annual-incomes.store');

            Route::put(
                '/annual-incomes/{id}',
                [AnnualIncomeController::class, 'update']
            )->name('annual-incomes.update');

            Route::delete(
                '/annual-incomes/{id}',
                [AnnualIncomeController::class, 'destroy']
            )->name('annual-incomes.destroy');

            Route::get(
                '/family-status',
                [FamilyStatusController::class, 'index']
            )->name('family-status.index');

            Route::post(
                '/family-status',
                [FamilyStatusController::class, 'store']
            )->name('family-status.store');

            Route::put(
                '/family-status/{id}',
                [FamilyStatusController::class, 'update']
            )->name('family-status.update');

            Route::delete(
                '/family-status/{id}',
                [FamilyStatusController::class, 'destroy']
            )->name('family-status.destroy');

            Route::get(
                '/marital-status',
                [MaritalStatusController::class, 'index']
            )->name('marital-status.index');

            Route::post(
                '/marital-status',
                [MaritalStatusController::class, 'store']
            )->name('marital-status.store');

            Route::put(
                '/marital-status/{id}',
                [MaritalStatusController::class, 'update']
            )->name('marital-status.update');

            Route::delete(
                '/marital-status/{id}',
                [MaritalStatusController::class, 'destroy']
            )->name('marital-status.destroy');

            Route::get(
                '/mother-tongues',
                [MotherTongueController::class, 'index']
            )->name('mother-tongues.index');

            Route::post(
                '/mother-tongues',
                [MotherTongueController::class, 'store']
            )->name('mother-tongues.store');

            Route::put(
                '/mother-tongues/{id}',
                [MotherTongueController::class, 'update']
            )->name('mother-tongues.update');

            Route::delete(
                '/mother-tongues/{id}',
                [MotherTongueController::class, 'destroy']
            )->name('mother-tongues.destroy');

            Route::get(
                '/heights',
                [HeightController::class, 'index']
            )->name('heights.index');

            Route::post(
                '/heights',
                [HeightController::class, 'store']
            )->name('heights.store');

            Route::put(
                '/heights/{id}',
                [HeightController::class, 'update']
            )->name('heights.update');

            Route::delete(
                '/heights/{id}',
                [HeightController::class, 'destroy']
            )->name('heights.destroy');

            Route::get(
                '/countries',
                [CountryController::class, 'index']
            )->name('countries.index');

            Route::post(
                '/countries',
                [CountryController::class, 'store']
            )->name('countries.store');

            Route::put(
                '/countries/{id}',
                [CountryController::class, 'update']
            )->name('countries.update');

            Route::delete(
                '/countries/{id}',
                [CountryController::class, 'destroy']
            )->name('countries.destroy');

            Route::get(
                '/states',
                [StateController::class, 'index']
            )->name('states.index');

            Route::post(
                '/states',
                [StateController::class, 'store']
            )->name('states.store');

            Route::put(
                '/states/{id}',
                [StateController::class, 'update']
            )->name('states.update');

            Route::delete(
                '/states/{id}',
                [StateController::class, 'destroy']
            )->name('states.destroy');

            Route::get(
                '/cities',
                [CityController::class, 'index']
            )->name('cities.index');

            Route::post(
                '/cities',
                [CityController::class, 'store']
            )->name('cities.store');

            Route::put(
                '/cities/{id}',
                [CityController::class, 'update']
            )->name('cities.update');

            Route::delete(
                '/cities/{id}',
                [CityController::class, 'destroy']
            )->name('cities.destroy');
        });
    });
});
