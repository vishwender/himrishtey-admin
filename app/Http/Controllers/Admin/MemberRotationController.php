<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberRotation;
use Illuminate\Http\Request;

class MemberRotationController extends Controller
{
    public function index()
    {

        $admin = auth('admin')->user();

        if (!$admin) {
            abort(403, 'Admin authentication required.');
        }
        dd($admin);
        $isSuperAdmin = $admin->hasRole('super-admin');

        $today = now()->startOfDay();

        $tomorrow = now()->copy()
            ->addDay()
            ->startOfDay();

        $dayAfterTomorrow = now()->copy()
            ->addDays(2)
            ->startOfDay();


        /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

        $query = MemberRotation::with('member');


        /*
    |--------------------------------------------------------------------------
    | Access Control
    |--------------------------------------------------------------------------
    */

        $admin = auth()->user();

        $isSuperAdmin = $admin->hasRole('super-admin');

        if (!$isSuperAdmin) {

            $query->where('user_id', $admin->id);
        }


        /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

        $statsQuery = clone $query;

        $totalRotations = (clone $statsQuery)->count();

        $todayRotations = (clone $statsQuery)
            ->whereBetween('next_rotation_at', [
                $today,
                $today->copy()->endOfDay()
            ])
            ->count();

        $tomorrowRotations = (clone $statsQuery)
            ->whereBetween('next_rotation_at', [
                $tomorrow,
                $tomorrow->copy()->endOfDay()
            ])
            ->count();

        $nextTwoDaysRotations = (clone $statsQuery)
            ->whereBetween('next_rotation_at', [
                $tomorrow,
                $dayAfterTomorrow->copy()->endOfDay()
            ])
            ->count();


        /*
    |--------------------------------------------------------------------------
    | Rotations
    |--------------------------------------------------------------------------
    */

        $rotations = $query
            ->orderBy('next_rotation_at', 'asc')
            ->paginate(20);


        /*
    |--------------------------------------------------------------------------
    | Load Admins From Central Database
    |--------------------------------------------------------------------------
    */

        $adminIds = $rotations
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values();

        $admins = Admin::whereIn('id', $adminIds)
            ->get()
            ->keyBy('id');


        return view('admin.rotations.index', compact(
            'rotations',
            'admins',
            'totalRotations',
            'todayRotations',
            'tomorrowRotations',
            'nextTwoDaysRotations'
        ));
    }
}
