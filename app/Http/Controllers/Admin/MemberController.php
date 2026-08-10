<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Display member listing.
     */
    public function index(Request $request)
    {
        $query = SiteMember::query();

        $query->addSelect([
            'membership_plan_name' => DB::connection('site')
                ->table('membership_plans')
                ->select('plan_name')
                ->whereColumn('membership_plans.id', 'members.plan_id')
                ->limit(1)
        ]);
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('profile_id', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Active / Inactive
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            if ($request->status === 'active') {

                $query->where('active', 'Yes');
            } elseif ($request->status === 'inactive') {

                $query->where(function ($q) {

                    $q->where('active', 'No')
                        ->orWhereNull('active');
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Trusted
        |--------------------------------------------------------------------------
        */

        if ($request->filled('trusted')) {

            if ($request->trusted === 'yes') {

                $query->where('is_trusted', 'Yes');
            } elseif ($request->trusted === 'no') {

                $query->where(function ($q) {

                    $q->where('is_trusted', 'No')
                        ->orWhereNull('is_trusted');
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Promoted
        |--------------------------------------------------------------------------
        */

        if ($request->filled('promoted')) {

            if ($request->promoted === 'yes') {

                $query->where('promoted', 'Yes');
            } elseif ($request->promoted === 'no') {

                $query->where(function ($q) {

                    $q->where('promoted', 'No')
                        ->orWhereNull('promoted');
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Visibility
        |--------------------------------------------------------------------------
        */

        if ($request->filled('visibility')) {

            if ($request->visibility === 'hidden') {

                $query->where('profile_hide', 'Yes');
            } elseif ($request->visibility === 'visible') {

                $query->where(function ($q) {

                    $q->where('profile_hide', 'No')
                        ->orWhereNull('profile_hide');
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Membership Plan
        |--------------------------------------------------------------------------
        */

        if ($request->filled('plan_id')) {

            if ($request->plan_id === 'none') {

                $query->where(function ($q) {

                    $q->where('plan_id', 0)
                        ->orWhereNull('plan_id');
                });
            } else {

                $query->where('plan_id', $request->plan_id);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($request->get('sort', 'newest')) {

            case 'oldest':

                $query->orderBy('id', 'asc');

                break;


            case 'name_asc':

                $query->orderBy('full_name', 'asc');

                break;


            case 'name_desc':

                $query->orderBy('full_name', 'desc');

                break;


            case 'profile_asc':

                $query->orderBy('profile_id', 'asc');

                break;


            case 'profile_desc':

                $query->orderBy('profile_id', 'desc');

                break;


            case 'newest':
            default:

                $query->orderBy('id', 'desc');

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Members
        |--------------------------------------------------------------------------
        */

        $members = $query
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Membership Plans
        |--------------------------------------------------------------------------
        */

        $plans = DB::connection('site')
            ->table('membership_plans')
            ->select([
                'id',
                'plan_name',
            ])
            ->orderBy('plan_name')
            ->get();


        return view('admin.members.index', compact(
            'members',
            'plans'
        ));
    }

    /**
     * Display a member's complete profile.
     */
    public function show(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Member
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Use SiteMember model instead of DB::table().
        |
        | DB::table()->first() returns stdClass.
        | SiteMember returns an Eloquent model.
        |
        */

        $member = SiteMember::query()
            ->where('id', $id)
            ->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }


        /*
        |--------------------------------------------------------------------------
        | Gallery Photos
        |--------------------------------------------------------------------------
        |
        | members.photo
        |      = main profile photo
        |
        | member_photos.photo
        |      = gallery photos
        |
        */

        $galleryPhotos = DB::connection('site')
            ->table('member_photos')
            ->where('member_id', $member->id)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Profile Completion
        |--------------------------------------------------------------------------
        |
        | Completion is calculated from the actual information
        | entered in the members table.
        |
        */

        $profileFields = [

            // Basic Information
            'full_name',
            'email',
            'mobile_number',
            'alternate_number',
            'whatsapp_number',
            'birth_date_time',
            'height',
            'gender',
            'blood_group',
            'health_info',
            'birth_place',
            'religion',
            'mother_tongue',
            'cast',
            'sub_cast',
            'gotra',
            'manglik',
            'marital_status',
            'no_of_child',

            // Education
            'about_my_education',
            'education',
            'any_other_qualifications',

            // Career
            'about_my_career',
            'employed_in',
            'occupation',
            'designation',
            'organization_name',
            'job_location',
            'annual_income',

            // Location
            'country_living_in',
            'state_living_in',
            'city_living_in',
            'address_living_in',
            'native_place',

            // Family
            'family_type',
            'family_status',
            'father_name',
            'father_occupation',
            'mother_name',
            'mother_occupation',
            'no_of_brothers',
            'no_of_sisters',
            'married_brothers',
            'married_sisters',
            'family_income',
            'about_family',

            // Lifestyle
            'diet',
            'is_drinking',
            'is_smoking',
            'about_me',
            'any_disability',

            // Partner Preferences
            'looking_for',
            'partner_age_from',
            'partner_age_to',
            'partner_country',
            'partner_religion',
            'partner_cast',
            'partner_height_from',
            'partner_height_to',
            'partner_education',
            'partner_mothertongue',
            'partner_annual_income_from',
            'partner_annual_income_to',
            'is_partner_manglik',
            'partner_occupation',
            'partner_state',
            'partner_city',
            'partner_diet',
            'is_partner_smoking',
            'is_partner_drinking',
            'about_my_partner',
        ];


        /*
        |--------------------------------------------------------------------------
        | Calculate Profile Completion
        |--------------------------------------------------------------------------
        */

        $totalFields = count($profileFields);

        $completedFields = 0;

        foreach ($profileFields as $field) {

            $value = $member->{$field} ?? null;

            if (
                $value !== null &&
                trim((string) $value) !== ''
            ) {
                $completedFields++;
            }
        }


        $profileCompletion = $totalFields > 0
            ? round(($completedFields / $totalFields) * 100)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        $returnUrl = $request->get('return');

        return view('admin.members.show', [
            'member' => $member,
            'galleryPhotos' => $galleryPhotos,
            'profileCompletion' => $profileCompletion,
            'completedFields' => $completedFields,
            'totalFields' => $totalFields,
            'returnUrl' => $returnUrl,
        ]);
    }

    /**
     * Activate / deactivate member.
     */
    public function toggleStatus($id)
    {
        $member = SiteMember::query()
            ->where('id', $id)
            ->firstOrFail();

        $member->active = $member->is_active
            ? 'No'
            : 'Yes';

        $member->save();

        return back()->with(
            'success',
            $member->active === 'Yes'
                ? 'Member activated successfully.'
                : 'Member deactivated successfully.'
        );
    }

    /**
     * Mark / remove trusted status.
     */
    public function toggleTrusted($id)
    {
        $member = SiteMember::query()
            ->where('id', $id)
            ->firstOrFail();

        $member->is_trusted =
            strtolower((string) $member->is_trusted) === 'yes'
            ? 'No'
            : 'Yes';

        $member->save();

        return back()->with(
            'success',
            $member->is_trusted === 'Yes'
                ? 'Member marked as trusted.'
                : 'Trusted status removed.'
        );
    }

    /**
     * Hide / show member profile.
     */
    public function toggleVisibility($id)
    {
        $member = SiteMember::query()
            ->where('id', $id)
            ->firstOrFail();

        $currentlyHidden =
            strtolower((string) $member->profile_hide) === 'yes';

        if ($currentlyHidden) {

            $member->profile_hide = 'No';
            $member->hidden_date = null;
            $member->hide_for_days = null;

            $message = 'Member profile is now visible.';
        } else {

            $member->profile_hide = 'Yes';
            $member->hidden_date = now()->format('Y-m-d H:i:s');

            $message = 'Member profile has been hidden.';
        }

        $member->save();

        return back()->with('success', $message);
    }

    /**
     * Promote / remove promotion.
     */
    public function togglePromoted($id)
    {
        $member = SiteMember::query()
            ->where('id', $id)
            ->firstOrFail();

        $member->promoted =
            strtolower((string) $member->promoted) === 'yes'
            ? 'No'
            : 'Yes';

        $member->save();

        return back()->with(
            'success',
            $member->promoted === 'Yes'
                ? 'Member profile promoted.'
                : 'Member promotion removed.'
        );
    }

    public function setProfilePhoto($memberId, $photoId)
    {
        $db = DB::connection('site');

        // Verify member exists
        $member = $db->table('members')
            ->where('id', $memberId)
            ->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }

        // Get the gallery photo belonging to this member
        $photo = $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->first();

        if (!$photo) {
            abort(404, 'Photo not found.');
        }

        // Set gallery photo as main profile photo
        $db->table('members')
            ->where('id', $memberId)
            ->update([
                'photo' => $photo->photo,
            ]);

        return back()->with(
            'success',
            'Profile photo updated successfully.'
        );
    }

    public function approvePhoto($memberId, $photoId)
    {
        $db = DB::connection('site');

        $photo = $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->first();

        if (!$photo) {
            abort(404, 'Photo not found.');
        }

        $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->update([
                'photo_approved' => 'Yes',
            ]);

        return back()->with(
            'success',
            'Photo approved successfully.'
        );
    }


    public function rejectPhoto($memberId, $photoId)
    {
        $db = DB::connection('site');

        $photo = $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->first();

        if (!$photo) {
            abort(404, 'Photo not found.');
        }

        $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->update([
                'photo_approved' => 'No',
            ]);

        return back()->with(
            'success',
            'Photo rejected successfully.'
        );
    }

    public function deletePhoto($memberId, $photoId)
    {
        $db = DB::connection('site');

        // Find member
        $member = $db->table('members')
            ->where('id', $memberId)
            ->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }

        // Find photo belonging to this member
        $photo = $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->first();

        if (!$photo) {
            abort(404, 'Photo not found.');
        }

        /*
    |--------------------------------------------------------------------------
    | Prevent deleting current profile photo
    |--------------------------------------------------------------------------
    */

        if ($member->photo === $photo->photo) {
            return back()->withErrors([
                'photo' => 'You cannot delete the current profile photo. Set another photo as the profile photo first.',
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Delete database record
    |--------------------------------------------------------------------------
    */

        $db->table('member_photos')
            ->where('id', $photoId)
            ->where('member_id', $memberId)
            ->delete();

        /*
    |--------------------------------------------------------------------------
    | Check whether the physical file is used elsewhere
    |--------------------------------------------------------------------------
    */

        $photoStillUsed = $db->table('member_photos')
            ->where('photo', $photo->photo)
            ->exists();

        /*
    |--------------------------------------------------------------------------
    | Delete physical file only if no other record uses it
    |--------------------------------------------------------------------------
    */

        if (!$photoStillUsed) {

            $photoPath = storage_path(
                'app/public/' . ltrim($photo->photo, '/')
            );

            if (is_file($photoPath)) {
                unlink($photoPath);
            }
        }

        return back()->with(
            'success',
            'Photo deleted successfully.'
        );
    }
}
