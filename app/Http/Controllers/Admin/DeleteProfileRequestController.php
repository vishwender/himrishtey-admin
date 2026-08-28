<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeleteProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteProfileRequestController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = DeleteProfileRequest::query()
            ->with('member')
            ->whereIn('id', function ($subQuery) {

                $subQuery
                    ->selectRaw('MAX(id)')
                    ->from('delete_profile_request')
                    ->groupBy('user_id');
            });

        $query->select('delete_profile_request.*')
            ->selectSub(function ($subQuery) {

                $subQuery
                    ->from('delete_profile_request as dpr_count')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn(
                        'dpr_count.user_id',
                        'delete_profile_request.user_id'
                    );
            }, 'request_count');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        |
        | Search by:
        | - Profile ID
        | - Name
        | - Email
        | - Mobile
        |
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('member', function ($q) use ($search) {

                $q->where('profile_id', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        |
        | 0 = Pending
        | 1 = Processed
        |
        | We can change these meanings later if your old system uses
        | different status values.
        |
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                (int) $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderByDesc('id');


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $requests = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Counts
        |--------------------------------------------------------------------------
        */

        $totalRequests = DeleteProfileRequest::query()
            ->distinct()
            ->count('user_id');

        $pendingRequests =
            DeleteProfileRequest::where('status', 0)->count();

        $processedRequests =
            DeleteProfileRequest::where('status', 1)->count();


        return view(
            'admin.delete-profile-requests.index',
            compact(
                'requests',
                'totalRequests',
                'pendingRequests',
                'processedRequests'
            )
        );
    }

    public function accept($id)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        $deleteRequest = DeleteProfileRequest::findOrFail($id);

        // Only pending requests can be accepted.
        if ((int) $deleteRequest->status !== 0) {
            return redirect()
                ->route('admin.delete-profile-requests.index')
                ->with('error', 'This request has already been processed.');
        }

        $deleteRequest->status = 1;
        $deleteRequest->save();

        return redirect()
            ->route('admin.delete-profile-requests.index')
            ->with('success', 'Profile deletion request accepted.');
    }


    public function reject($id)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        $deleteRequest = DeleteProfileRequest::findOrFail($id);

        // Only pending requests can be rejected.
        if ((int) $deleteRequest->status !== 0) {
            return redirect()
                ->route('admin.delete-profile-requests.index')
                ->with('error', 'This request has already been processed.');
        }

        $deleteRequest->status = 2;
        $deleteRequest->save();

        return redirect()
            ->route('admin.delete-profile-requests.index')
            ->with('success', 'Profile deletion request rejected.');
    }
}
