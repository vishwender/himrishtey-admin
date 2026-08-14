<?php

namespace App\Http\Controllers\Admin;

use App\Services\MemberPhotoService;
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
    public function show(
        MemberPhotoService $photoService,
        Request $request,
        $id
    ) {
        /*
    |--------------------------------------------------------------------------
    | Member
    |--------------------------------------------------------------------------
    |
    | SiteMember uses the selected site's database connection.
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
    | Membership Plans
    |--------------------------------------------------------------------------
    */

        $plans = DB::connection('site')
            ->table('membership_plans')
            ->select([
                'id',
                'membership_type',
                'plan_name',
                'duration_days',
                'view_contact',
                'view_profile',
                'plan_cost',
                'discount_percentage',
                'final_cost',
            ])
            ->orderBy('plan_name')
            ->get();


        /*
    |--------------------------------------------------------------------------
    | Current Membership Plan
    |--------------------------------------------------------------------------
    */

        $membershipPlan = null;

        $membershipExpiryDate = null;


        if (
            !empty($member->plan_id) &&
            (int) $member->plan_id > 0
        ) {

            $membershipPlan = $plans->firstWhere(
                'id',
                (int) $member->plan_id
            );


            /*
        |--------------------------------------------------------------------------
        | Calculate Membership Expiry
        |--------------------------------------------------------------------------
        */

            if (
                $membershipPlan &&
                !empty($member->plan_activation_date) &&
                !empty($membershipPlan->duration_days)
            ) {

                try {

                    $membershipExpiryDate = \Carbon\Carbon::parse(
                        $member->plan_activation_date
                    )->addDays(
                        max(
                            0,
                            (int) $membershipPlan->duration_days - 1
                        )
                    );
                } catch (\Exception $e) {

                    $membershipExpiryDate = null;
                }
            }
        }

        /*
|--------------------------------------------------------------------------
| Membership Usage
|--------------------------------------------------------------------------
*/

        $profileViewsUsed = 0;
        $contactViewsUsed = 0;

        $profileViewsAllowed = 0;
        $contactViewsAllowed = 0;

        $profileViewsRemaining = 0;
        $contactViewsRemaining = 0;


        if ($membershipPlan) {

            /*
    |--------------------------------------------------------------------------
    | Plan Limits
    |--------------------------------------------------------------------------
    */

            $profileViewsAllowed = max(
                0,
                (int) $membershipPlan->view_profile
            );

            $contactViewsAllowed = max(
                0,
                (int) $membershipPlan->view_contact
            );


            /*
    |--------------------------------------------------------------------------
    | Profile Views Used
    |--------------------------------------------------------------------------
    */

            $profileViewsUsed = DB::connection('site')
                ->table('profile_viewed')
                ->where('member_id', $member->id)
                ->count();


            /*
    |--------------------------------------------------------------------------
    | Contact Views Used
    |--------------------------------------------------------------------------
    */

            $contactViewsUsed = DB::connection('site')
                ->table('contact_viewed')
                ->where('member_id', $member->id)
                ->count();


            /*
    |--------------------------------------------------------------------------
    | Remaining
    |--------------------------------------------------------------------------
    */

            $profileViewsRemaining = max(
                0,
                $profileViewsAllowed - $profileViewsUsed
            );

            $contactViewsRemaining = max(
                0,
                $contactViewsAllowed - $contactViewsUsed
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Membership Payment History
        |--------------------------------------------------------------------------
        */

        $membershipPayments = DB::connection('site')
            ->table('payments')
            ->leftJoin(
                'membership_plans',
                'membership_plans.id',
                '=',
                'payments.plan_id'
            )
            ->where('payments.member_id', $member->id)
            ->select([
                'payments.id',
                'payments.payment_date',
                'payments.payment_id',
                'payments.amount',
                'payments.remarks',
                'payments.plan_id',
                'membership_plans.plan_name',
            ])
            ->orderByDesc('payments.payment_date')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Member Activity Overview
        |--------------------------------------------------------------------------
        */

        $activityCounts = [

            /*
            |--------------------------------------------------------------------------
            | Shortlisted Profiles
            |--------------------------------------------------------------------------
            */

            'shortlisted' => DB::connection('site')
                ->table('short_listed')
                ->where('member_id', $member->id)
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Sent Interests
            |--------------------------------------------------------------------------
            */

            'sent_interests' => DB::connection('site')
                ->table('sent_interests')
                ->where('member_id', $member->id)
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Received Interests
            |--------------------------------------------------------------------------
            */

            'received_interests' => DB::connection('site')
                ->table('sent_interests')
                ->where('profile_id', $member->id)
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Profile Views
            |--------------------------------------------------------------------------
            */

            'profile_views' => DB::connection('site')
                ->table('profile_viewed')
                ->where('member_id', $member->id)
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Contact Views
            |--------------------------------------------------------------------------
            */

            'contact_views' => DB::connection('site')
                ->table('contact_viewed')
                ->where('member_id', $member->id)
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Wallet / Membership Payments
            |--------------------------------------------------------------------------
            */

            'wallet_payments' => DB::connection('site')
                ->table('payments')
                ->where('member_id', $member->id)
                ->count(),

        ];

        /*
            |--------------------------------------------------------------------------
            | Relationship Managers
            |--------------------------------------------------------------------------
            */

        $relationshipManagers = DB::connection('site')
            ->table('users')
            ->select([
                'id',
                'display_name',
            ])
            ->whereNotNull('display_name')
            ->where('display_name', '!=', '')
            ->orderBy('display_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Profile Photo URL
        |--------------------------------------------------------------------------
        */

        $member->photo_url = $photoService->url(
            $member->photo
        );


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
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Gallery Photo URLs
        |--------------------------------------------------------------------------
        */

        foreach ($galleryPhotos as $photo) {

            $photo->photo_url = $photoService->url(
                $photo->photo
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Profile Completion
        |--------------------------------------------------------------------------
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
        | Return URL
        |--------------------------------------------------------------------------
        */

        $returnUrl = $request->get('return');


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('admin.members.show', [

            'member' => $member,
            'galleryPhotos' => $galleryPhotos,
            'profileCompletion' => $profileCompletion,
            'completedFields' => $completedFields,
            'totalFields' => $totalFields,
            'plans' => $plans,
            'membershipPlan' => $membershipPlan,
            'membershipExpiryDate' => $membershipExpiryDate,
            'profileViewsUsed' => $profileViewsUsed,
            'profileViewsAllowed' => $profileViewsAllowed,
            'profileViewsRemaining' => $profileViewsRemaining,
            'contactViewsUsed' => $contactViewsUsed,
            'contactViewsAllowed' => $contactViewsAllowed,
            'contactViewsRemaining' => $contactViewsRemaining,
            'membershipPayments' => $membershipPayments,
            'activityCounts' => $activityCounts,
            'relationshipManagers' => $relationshipManagers,
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

    /**
     * Edit member profile.
     */
    public function edit($id)
    {
        $member = DB::connection('site')
            ->table('members')
            ->where('id', $id)
            ->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }


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
                'membership_type',
                'duration_days',
                'view_contact',
                'view_profile',
                'plan_cost',
                'discount_percentage',
                'final_cost',
            ])
            ->orderBy('plan_name')
            ->get();


        return view('admin.members.edit', [
            'member' => $member,
            'plans' => $plans,
        ]);
    }

    /**
     * Update member profile.
     */
    public function update(Request $request, $id)
    {
        $db = DB::connection('site');


        /*
    |--------------------------------------------------------------------------
    | Find Member
    |--------------------------------------------------------------------------
    */

        $member = $db->table('members')
            ->where('id', $id)
            ->first();


        if (!$member) {
            abort(404, 'Member not found.');
        }


        /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

        $validated = $request->validate([

            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'mobile_number' => [
                'required',
                'string',
                'max:255',
            ],

            'alternate_number' => [
                'nullable',
                'string',
                'max:233',
            ],

            'whatsapp_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:50',
            ],

            'birth_date_time' => [
                'nullable',
                'string',
                'max:255',
            ],

            'height' => [
                'nullable',
                'string',
                'max:255',
            ],

            'blood_group' => [
                'nullable',
                'string',
                'max:255',
            ],

            'religion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'mother_tongue' => [
                'nullable',
                'string',
                'max:255',
            ],

            'cast' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sub_cast' => [
                'nullable',
                'string',
                'max:255',
            ],

            'gotra' => [
                'nullable',
                'string',
                'max:255',
            ],

            'manglik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'marital_status' => [
                'nullable',
                'string',
                'max:255',
            ],

            'education' => [
                'nullable',
                'string',
                'max:255',
            ],

            'employed_in' => [
                'nullable',
                'string',
                'max:255',
            ],

            'occupation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'organization_name' => [
                'nullable',
                'string',
                'max:244',
            ],

            'job_location' => [
                'nullable',
                'string',
                'max:234',
            ],

            'annual_income' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country_living_in' => [
                'nullable',
                'string',
                'max:255',
            ],

            'state_living_in' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city_living_in' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address_living_in' => [
                'nullable',
                'string',
                'max:255',
            ],

            'native_place' => [
                'nullable',
                'string',
                'max:255',
            ],

            'family_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'family_status' => [
                'nullable',
                'string',
                'max:255',
            ],

            'father_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'father_occupation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'mother_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'mother_occupation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'diet' => [
                'nullable',
                'string',
                'max:234',
            ],

            'is_drinking' => [
                'nullable',
                'string',
                'max:234',
            ],

            'is_smoking' => [
                'nullable',
                'string',
                'max:234',
            ],

            'any_disability' => [
                'nullable',
                'string',
                'max:255',
            ],

            'about_me' => [
                'nullable',
                'string',
            ],

            'about_family' => [
                'nullable',
                'string',
            ],

            'about_my_education' => [
                'nullable',
                'string',
                'max:244',
            ],

            'about_my_career' => [
                'nullable',
                'string',
                'max:255',
            ],

            'about_my_partner' => [
                'nullable',
                'string',
                'max:255',
            ],

            'plan_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'profile_hide' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'relationship_manager' => [
                'nullable',
                'string',
                'max:255',
            ],

            'looking_for' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_age_from' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_age_to' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_country' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_religion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_cast' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_education' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_mothertongue' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_annual_income_from' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_annual_income_to' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_partner_manglik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_occupation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_state' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partner_diet' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_partner_smoking' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_partner_drinking' => [
                'nullable',
                'string',
                'max:255',
            ],

            'about_my_partner' => [
                'nullable',
                'string',
                'max:255',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Partner Height From
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('partner_height_from_feet') &&
            $request->filled('partner_height_from_inches')
        ) {
            $feet = (int) $request->partner_height_from_feet;
            $inches = (int) $request->partner_height_from_inches;

            $validated['partner_height_from'] =
                round($feet + ($inches / 12), 2);
        } else {
            $validated['partner_height_from'] = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Partner Height To
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('partner_height_to_feet') &&
            $request->filled('partner_height_to_inches')
        ) {
            $feet = (int) $request->partner_height_to_feet;
            $inches = (int) $request->partner_height_to_inches;

            $validated['partner_height_to'] =
                round($feet + ($inches / 12), 2);
        } else {
            $validated['partner_height_to'] = 0;
        }


        unset(
            $validated['partner_height_from_feet'],
            $validated['partner_height_from_inches'],
            $validated['partner_height_to_feet'],
            $validated['partner_height_to_inches']
        );
        /*
        |--------------------------------------------------------------------------
        | Update Member
        |--------------------------------------------------------------------------
        */

        $db->table('members')
            ->where('id', $id)
            ->update($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.members.show', $id)
            ->with('success', 'Member profile updated successfully.');
    }

    public function changeMembership(
        Request $request,
        $memberId
    ) {
        /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

        $validated = $request->validate([
            'plan_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'plan_activation_date' => [
                'required',
                'date',
            ],
        ]);


        /*
    |--------------------------------------------------------------------------
    | Selected Site Connection
    |--------------------------------------------------------------------------
    */

        $db = DB::connection('site');


        /*
    |--------------------------------------------------------------------------
    | Find Member
    |--------------------------------------------------------------------------
    */

        $member = $db->table('members')
            ->where('id', $memberId)
            ->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }


        /*
    |--------------------------------------------------------------------------
    | Find Plan
    |--------------------------------------------------------------------------
    */

        $plan = $db->table('membership_plans')
            ->where('id', $validated['plan_id'])
            ->first();

        if (!$plan) {

            return back()
                ->withErrors([
                    'plan_id' => 'The selected membership plan does not exist.',
                ])
                ->withInput();
        }


        /*
    |--------------------------------------------------------------------------
    | Calculate Expiry
    |--------------------------------------------------------------------------
    */

        $activationDate = \Carbon\Carbon::parse(
            $validated['plan_activation_date']
        );

        $expiryDate = $activationDate->copy()->addDays(
            max(
                0,
                (int) $plan->duration_days - 1
            )
        );


        /*
    |--------------------------------------------------------------------------
    | Update Member
    |--------------------------------------------------------------------------
    */

        $db->table('members')
            ->where('id', $memberId)
            ->update([
                'plan_id' => $plan->id,
                'plan_activation_date' => $activationDate->format('Y-m-d'),
            ]);


        /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route('admin.members.show', [
                'id' => $memberId,
            ])
            ->with(
                'success',
                "Membership changed to {$plan->plan_name} successfully."
            );
    }

    public function updateRelationshipManager(
        Request $request,
        $memberId
    ) {
        $validated = $request->validate([
            'relationship_manager' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        /*
    |--------------------------------------------------------------------------
    | Find Member
    |--------------------------------------------------------------------------
    */

        $member = DB::connection('site')
            ->table('members')
            ->where('id', $memberId)
            ->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }


        /*
    |--------------------------------------------------------------------------
    | Update Relationship Manager
    |--------------------------------------------------------------------------
    */

        DB::connection('site')
            ->table('members')
            ->where('id', $memberId)
            ->update([
                'relationship_manager' =>
                $validated['relationship_manager'] ?? null,
            ]);


        /*
    |--------------------------------------------------------------------------
    | Return
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->back()
            ->with(
                'success',
                'Relationship manager updated successfully.'
            );
    }

    public function updateRemarks(Request $request, $memberId)
    {
        $validated = $request->validate([
            'remarks' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        $member = DB::connection('site')
            ->table('members')
            ->where('id', $memberId)
            ->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }

        DB::connection('site')
            ->table('members')
            ->where('id', $memberId)
            ->update([
                'remarks' => $validated['remarks'] ?? null,
            ]);

        return redirect()
            ->back()
            ->with('success', 'Member remarks updated successfully.');
    }
}
