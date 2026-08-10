@extends('admin.layout')

@section('content')

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    {{ session('success') }}

    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h1 class="h3 mb-1">Member Profile</h1>

            <div class="text-muted">
                Profile ID:
                <strong>{{ $member->profile_id }}</strong>
            </div>
        </div>

        <div class="mt-3 mt-md-0">

            @php
            $backUrl = route('admin.members.index');

            if (!empty($returnUrl)) {
            $parsedUrl = parse_url($returnUrl);

            if (
            !isset($parsedUrl['host']) ||
            $parsedUrl['host'] === request()->getHost()
            ) {
            $backUrl = $returnUrl;
            }
            }
            @endphp

            <a
                href="{{ $backUrl }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Members

            </a>

        </div>

    </div>


    {{-- Profile Header Card --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                {{-- Profile Photo --}}
                <div class="col-md-2 text-center mb-3 mb-md-0">

                    @if(!empty($member->photo))

                    <img
                        src="{{ asset($member->photo) }}"
                        alt="{{ $member->full_name }}"
                        class="rounded-circle shadow-sm"
                        style="
                                width: 130px;
                                height: 130px;
                                object-fit: cover;
                            ">

                    @else

                    <div
                        class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                        style="
                                width: 130px;
                                height: 130px;
                            ">
                        <span class="text-muted">
                            No Photo
                        </span>
                    </div>

                    @endif

                </div>


                {{-- Basic Information --}}
                <div class="col-md-6">

                    <h2 class="h4 mb-2">
                        {{ $member->full_name }}
                    </h2>

                    <div class="mb-2">

                        <span class="badge bg-light text-dark border me-1">
                            {{ $member->profile_id }}
                        </span>

                        @if(strtolower(trim($member->active ?? '')) === 'yes')

                        <span class="badge bg-success">
                            Active
                        </span>

                        @else

                        <span class="badge bg-secondary">
                            Inactive
                        </span>

                        @endif

                    </div>


                    <div class="text-muted">

                        @if(!empty($member->gender))
                        <span class="me-3">
                            {{ $member->gender }}
                        </span>
                        @endif

                        @if(!empty($member->birth_date_time))
                        <span class="me-3">
                            {{ $member->birth_date_time }}
                        </span>
                        @endif

                    </div>


                    @if(!empty($member->mobile_number))

                    <div class="mt-2">

                        <strong>Mobile:</strong>
                        {{ $member->mobile_number }}

                    </div>

                    @endif


                    @if(!empty($member->email))

                    <div>

                        <strong>Email:</strong>
                        {{ $member->email }}

                    </div>

                    @endif

                </div>


                {{-- Profile Completion --}}
                <div class="col-md-4 mt-4 mt-md-0">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="fw-semibold">
                            Profile Completion
                        </span>

                        <span class="fw-bold">
                            {{ $profileCompletion }}%
                        </span>

                    </div>


                    <div
                        class="progress"
                        style="height: 10px;">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $profileCompletion }}%;"
                            aria-valuenow="{{ $profileCompletion }}"
                            aria-valuemin="0"
                            aria-valuemax="100"></div>

                    </div>


                    <div class="small text-muted mt-2">

                        {{ $completedFields }}
                        of
                        {{ $totalFields }}
                        profile fields completed

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
    Photos
========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-images me-2"></i>
                Photos
            </h5>
        </div>

        <div class="card-body">

            {{-- Main Profile Photo --}}
            <div class="mb-4">

                <h6 class="text-muted mb-3">
                    Profile Photo
                </h6>

                @if(!empty($member->photo))

                <div class="position-relative d-inline-block">

                    <img
                        src="{{ asset('storage/' . $member->photo) }}"
                        alt="{{ $member->full_name }}"
                        class="rounded-3 shadow-sm"
                        style="width:180px;height:180px;object-fit:cover;">

                </div>

                @else

                <div
                    class="d-flex align-items-center justify-content-center bg-light rounded-3"
                    style="width:180px;height:180px;">

                    <div class="text-center text-muted">
                        <i class="bi bi-person fs-1"></i>
                        <div>No profile photo</div>
                    </div>

                </div>

                @endif

            </div>


            {{-- Gallery --}}
            <div>

                <h6 class="text-muted mb-3">
                    Gallery
                </h6>

                @if($galleryPhotos->count())

                <div class="row g-3">

                    @foreach($galleryPhotos as $photo)

                    <div class="col-6 col-md-4 col-lg-3">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="position-relative">

                                <img
                                    src="{{ asset('storage/' . $photo->photo) }}"
                                    alt="Member photo"
                                    class="card-img-top"
                                    style="height:180px;object-fit:cover;">

                            </div>

                            <div class="card-body p-2">

                                <div class="d-flex justify-content-between align-items-center">

                                    <small class="text-muted">
                                        Photo #{{ $photo->id }}
                                    </small>

                                    @if($photo->photo_approved === 'Yes')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                    @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                    @endif

                                </div>

                                <div class="mt-2">

                                    @if($photo->photo_privacy == 1)

                                    <small class="text-muted">
                                        <i class="bi bi-globe me-1"></i>
                                        Public
                                    </small>

                                    @else

                                    <small class="text-muted">
                                        <i class="bi bi-lock me-1"></i>
                                        Private
                                    </small>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

                @else

                <div class="text-center py-4 text-muted">

                    <i class="bi bi-images fs-1"></i>

                    <p class="mt-2 mb-0">
                        No gallery photos found.
                    </p>

                </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Basic Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                Basic Information
            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Full Name
                    </small>

                    <strong>
                        {{ $member->full_name ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Gender
                    </small>

                    <strong>
                        {{ $member->gender ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Date of Birth
                    </small>

                    <strong>
                        {{ $member->birth_date_time ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Height
                    </small>

                    <strong>
                        {{ $member->height ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Blood Group
                    </small>

                    <strong>
                        {{ $member->blood_group ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Marital Status
                    </small>

                    <strong>
                        {{ $member->marital_status ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Religion
                    </small>

                    <strong>
                        {{ $member->religion ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Mother Tongue
                    </small>

                    <strong>
                        {{ $member->mother_tongue ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Caste
                    </small>

                    <strong>
                        {{ $member->cast ?: '-' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

    {{-- About Me --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                About Me
            </h5>
        </div>

        <div class="card-body">

            @if(!empty($member->about_me))

            <p class="mb-0">
                {{ $member->about_me }}
            </p>

            @else

            <p class="text-muted mb-0">
                No information provided.
            </p>

            @endif

        </div>

    </div>


    {{-- Education & Career --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                Education & Career
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Education --}}
                <div class="col-md-6 mb-4">

                    <h6 class="fw-semibold mb-3">
                        Education
                    </h6>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Education
                        </small>

                        <strong>
                            {{ $member->education ?: '-' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Other Qualifications
                        </small>

                        <strong>
                            {{ $member->any_other_qualifications ?: '-' }}
                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            About Education
                        </small>

                        <div>
                            {{ $member->about_my_education ?: '-' }}
                        </div>

                    </div>

                </div>


                {{-- Career --}}
                <div class="col-md-6 mb-4">

                    <h6 class="fw-semibold mb-3">
                        Career
                    </h6>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Employed In
                        </small>

                        <strong>
                            {{ $member->employed_in ?: '-' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Occupation
                        </small>

                        <strong>
                            {{ $member->occupation ?: '-' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Designation
                        </small>

                        <strong>
                            {{ $member->designation ?: '-' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Organization
                        </small>

                        <strong>
                            {{ $member->organization_name ?: '-' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Job Location
                        </small>

                        <strong>
                            {{ $member->job_location ?: '-' }}
                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Annual Income
                        </small>

                        <strong>
                            {{ $member->annual_income ?: '-' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Location --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                Location
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        Country
                    </small>

                    <strong>
                        {{ $member->country_living_in ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        State
                    </small>

                    <strong>
                        {{ $member->state_living_in ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted d-block">
                        City
                    </small>

                    <strong>
                        {{ $member->city_living_in ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Address
                    </small>

                    <strong>
                        {{ $member->address_living_in ?: '-' }}
                    </strong>

                </div>


                <div class="col-md-6 mb-3">

                    <small class="text-muted d-block">
                        Native Place
                    </small>

                    <strong>
                        {{ $member->native_place ?: '-' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

    {{-- Family Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Family Information</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">Family Type</small>
                    <strong>{{ $member->family_type ?: '-' }}</strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">Family Status</small>
                    <strong>{{ $member->family_status ?: '-' }}</strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">Family Income</small>
                    <strong>{{ $member->family_income ?: '-' }}</strong>
                </div>

                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">Father's Name</small>
                    <strong>{{ $member->father_name ?: '-' }}</strong>
                </div>

                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">Father's Occupation</small>
                    <strong>{{ $member->father_occupation ?: '-' }}</strong>
                </div>

                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">Mother's Name</small>
                    <strong>{{ $member->mother_name ?: '-' }}</strong>
                </div>

                <div class="col-md-6 mb-3">
                    <small class="text-muted d-block">Mother's Occupation</small>
                    <strong>{{ $member->mother_occupation ?: '-' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Brothers</small>
                    <strong>{{ $member->no_of_brothers ?: '0' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Married Brothers</small>
                    <strong>{{ $member->married_brothers ?: '0' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Sisters</small>
                    <strong>{{ $member->no_of_sisters ?: '0' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Married Sisters</small>
                    <strong>{{ $member->married_sisters ?: '0' }}</strong>
                </div>

                <div class="col-12">
                    <small class="text-muted d-block">About Family</small>

                    @if(!empty($member->about_family))
                    <p class="mb-0">
                        {{ $member->about_family }}
                    </p>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </div>

            </div>

        </div>

    </div>

    {{-- Lifestyle --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Lifestyle & Health</h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Diet</small>
                    <strong>{{ $member->diet ?: '-' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Drinking</small>
                    <strong>{{ $member->is_drinking ?: '-' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Smoking</small>
                    <strong>{{ $member->is_smoking ?: '-' }}</strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">Disability</small>
                    <strong>{{ $member->any_disability ?: '-' }}</strong>
                </div>

                <div class="col-12">
                    <small class="text-muted d-block">Health Information</small>

                    @if(!empty($member->health_info))
                    <p class="mb-0">
                        {{ $member->health_info }}
                    </p>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </div>

            </div>

        </div>

    </div>
    {{-- Partner Preferences --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Partner Preferences</h5>
        </div>

        <div class="card-body">

            {{-- Basic Preferences --}}
            <h6 class="fw-semibold mb-3">
                Basic Preferences
            </h6>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Looking For
                    </small>
                    <strong>
                        {{ $member->looking_for ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Age From
                    </small>
                    <strong>
                        {{ $member->partner_age_from ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Age To
                    </small>
                    <strong>
                        {{ $member->partner_age_to ?: '-' }}
                    </strong>
                </div>

            </div>


            <hr class="my-4">


            {{-- Religion & Background --}}
            <h6 class="fw-semibold mb-3">
                Religion & Background
            </h6>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Country
                    </small>
                    <strong>
                        {{ $member->partner_country ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Religion
                    </small>
                    <strong>
                        {{ $member->partner_religion ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Caste
                    </small>
                    <strong>
                        {{ $member->partner_cast ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Mother Tongue
                    </small>
                    <strong>
                        {{ $member->partner_mothertongue ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Manglik
                    </small>
                    <strong>
                        {{ $member->is_partner_manglik ?: '-' }}
                    </strong>
                </div>

            </div>


            <hr class="my-4">


            {{-- Education & Career --}}
            <h6 class="fw-semibold mb-3">
                Education & Career
            </h6>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Education
                    </small>
                    <strong>
                        {{ $member->partner_education ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Occupation
                    </small>
                    <strong>
                        {{ $member->partner_occupation ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Annual Income From
                    </small>
                    <strong>
                        {{ $member->partner_annual_income_from ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Annual Income To
                    </small>
                    <strong>
                        {{ $member->partner_annual_income_to ?: '-' }}
                    </strong>
                </div>

            </div>


            <hr class="my-4">


            {{-- Height & Location --}}
            <h6 class="fw-semibold mb-3">
                Height & Location
            </h6>

            <div class="row">

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        Height From
                    </small>
                    <strong>
                        {{ $member->partner_height_from ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        Height To
                    </small>
                    <strong>
                        {{ $member->partner_height_to ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        State
                    </small>
                    <strong>
                        {{ $member->partner_state ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-3 mb-3">
                    <small class="text-muted d-block">
                        City
                    </small>
                    <strong>
                        {{ $member->partner_city ?: '-' }}
                    </strong>
                </div>

            </div>


            <hr class="my-4">


            {{-- Lifestyle Preferences --}}
            <h6 class="fw-semibold mb-3">
                Lifestyle Preferences
            </h6>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Diet
                    </small>
                    <strong>
                        {{ $member->partner_diet ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Smoking
                    </small>
                    <strong>
                        {{ $member->is_partner_smoking ?: '-' }}
                    </strong>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted d-block">
                        Drinking
                    </small>
                    <strong>
                        {{ $member->is_partner_drinking ?: '-' }}
                    </strong>
                </div>

            </div>


            <hr class="my-4">


            {{-- About Partner --}}
            <h6 class="fw-semibold mb-3">
                About Desired Partner
            </h6>

            @if(!empty($member->about_my_partner))

            <p class="mb-0">
                {{ $member->about_my_partner }}
            </p>

            @else

            <p class="text-muted mb-0">
                No information provided.
            </p>

            @endif

        </div>

    </div>
    {{-- Membership & Account Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Membership & Account Information</h5>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Member Type --}}
                <div class="col-md-4 mb-4">
                    <small class="text-muted d-block">
                        Member Type
                    </small>

                    <strong>
                        {{ $member->member_type ?: '-' }}
                    </strong>
                </div>

                {{-- Membership Plan --}}
                <div class="col-md-4 mb-4">
                    <small class="text-muted d-block">
                        Membership Plan
                    </small>

                    <strong>
                        {{ $member->plan_id ?: '-' }}
                    </strong>
                </div>

                {{-- Plan Activation Date --}}
                <div class="col-md-4 mb-4">
                    <small class="text-muted d-block">
                        Plan Activation Date
                    </small>

                    <strong>
                        {{ $member->plan_activation_date ?: '-' }}
                    </strong>
                </div>

                {{-- Registration Through --}}
                <div class="col-md-4 mb-4">
                    <small class="text-muted d-block">
                        Registration Through
                    </small>

                    <strong>
                        {{ $member->register_through ?: '-' }}
                    </strong>
                </div>

                {{-- Registration Date --}}
                <div class="col-md-4 mb-4">
                    <small class="text-muted d-block">
                        Registration Date
                    </small>

                    <strong>
                        {{ $member->registration_date ?: '-' }}
                    </strong>
                </div>

                {{-- Activation Number --}}
                <div class="col-md-4 mb-4">
                    <small class="text-muted d-block">
                        Activation Number
                    </small>

                    <strong>
                        {{ $member->activation_number ?: '0' }}
                    </strong>
                </div>

            </div>

            <hr class="my-2">

            <h6 class="fw-semibold mb-3">
                Profile Status
            </h6>

            <div class="row">

                {{-- Active --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Account Status
                    </small>

                    @if($member->active)

                    <span class="badge bg-success">
                        Active
                    </span>

                    @else

                    <span class="badge bg-secondary">
                        Inactive
                    </span>

                    @endif

                </div>

                {{-- Photo Approved --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Photo Approved
                    </small>

                    <strong>
                        {{ $member->photo_approved ?: '-' }}
                    </strong>

                </div>

                {{-- Trusted --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Trusted Profile
                    </small>

                    <strong>
                        {{ $member->is_trusted ?: '-' }}
                    </strong>

                </div>

                {{-- Profile Hidden --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Profile Visibility
                    </small>

                    <strong>
                        {{ $member->profile_hide ?: '-' }}
                    </strong>

                </div>

                {{-- Profile Completed --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Profile Completion
                    </small>

                    <strong>
                        {{ $member->profile_completed }}%
                    </strong>

                </div>

                {{-- Promoted --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Promoted
                    </small>

                    <strong>
                        {{ $member->promoted ?: '-' }}
                    </strong>

                </div>

                {{-- Profile Views --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Profile Views
                    </small>

                    <strong>
                        {{ $member->profile_view_count ?: '0' }}
                    </strong>

                </div>

                {{-- Assigned To --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Assigned To
                    </small>

                    <strong>
                        {{ $member->assigned_to ?: '-' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

    {{-- Photos & Gallery --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Photos & Gallery</h5>
        </div>

        <div class="card-body">

            {{-- Main Profile Photo --}}
            <div class="mb-4">

                <h6 class="fw-semibold mb-3">
                    Profile Photo
                </h6>

                <div class="row">

                    <div class="col-md-3">

                        @if(!empty($member->photo))

                        <div class="border rounded-3 overflow-hidden bg-light">

                            <img
                                src="{{ asset('storage/' . $member->photo) }}"
                                alt="{{ $member->full_name }}"
                                class="img-fluid w-100"
                                style="height: 220px; object-fit: cover;">

                        </div>

                        @else

                        <div
                            class="border rounded-3 bg-light d-flex align-items-center justify-content-center"
                            style="height: 220px;">
                            <span class="text-muted">
                                No profile photo
                            </span>
                        </div>

                        @endif

                    </div>

                    <div class="col-md-9">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">
                                    Photo
                                </small>

                                <strong>
                                    {{ $member->photo ?: 'Not uploaded' }}
                                </strong>

                            </div>

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">
                                    Approval
                                </small>

                                @if(strtolower((string) $member->photo_approved) === 'yes')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    {{ $member->photo_approved ?: 'Not Approved' }}
                                </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <hr class="my-4">


            {{-- Gallery --}}
            {{-- =========================================================
    Gallery
========================================================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <h5 class="mb-4">
                        <i class="bi bi-images me-2"></i>
                        Gallery
                    </h5>


                    @if($galleryPhotos->count())

                    <div class="row g-3">

                        @foreach($galleryPhotos as $photo)

                        <div class="col-6 col-md-4 col-lg-3">

                            <div class="card border-0 shadow-sm h-100">


                                {{-- =================================================
                                PHOTO
                            ================================================== --}}

                                <div class="position-relative">

                                    <img
                                        src="{{ asset('storage/' . $photo->photo) }}"
                                        alt="{{ $member->full_name }} photo"
                                        class="card-img-top"
                                        style="height:180px; object-fit:cover;">


                                    {{-- Current Profile Photo Badge --}}
                                    @if($member->photo === $photo->photo)

                                    <span
                                        class="position-absolute top-0 end-0 m-2 badge bg-success">

                                        <i class="bi bi-person-check me-1"></i>
                                        Profile

                                    </span>

                                    @endif

                                </div>


                                {{-- =================================================
                                PHOTO INFORMATION
                            ================================================== --}}

                                <div class="card-body p-3">


                                    {{-- Photo ID --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2">

                                        <small class="text-muted">

                                            Photo #{{ $photo->id }}

                                        </small>


                                        {{-- Approval Status --}}
                                        @if($photo->photo_approved === 'Yes')

                                        <span class="badge bg-success">

                                            <i class="bi bi-check-circle me-1"></i>
                                            Approved

                                        </span>

                                        @else

                                        <span class="badge bg-warning text-dark">

                                            <i class="bi bi-clock me-1"></i>
                                            Pending

                                        </span>

                                        @endif

                                    </div>


                                    {{-- =================================================
                                    PRIVACY
                                ================================================== --}}

                                    <div class="mb-3">

                                        @if($photo->photo_privacy == 1)

                                        <small class="text-muted">

                                            <i class="bi bi-globe me-1"></i>
                                            Public

                                        </small>

                                        @else

                                        <small class="text-muted">

                                            <i class="bi bi-lock me-1"></i>
                                            Private

                                        </small>

                                        @endif

                                    </div>


                                    {{-- =================================================
                                    APPROVE / REJECT
                                ================================================== --}}

                                    @if($photo->photo_approved === 'Yes')

                                    {{-- Reject Photo --}}

                                    <form
                                        action="{{ route('admin.members.photos.reject', [
                                            'memberId' => $member->id,
                                            'photoId' => $photo->id
                                        ]) }}"
                                        method="POST"
                                        class="mb-2">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger w-100"
                                            onclick="return confirm('Are you sure you want to reject this photo?')">

                                            <i class="bi bi-x-circle me-1"></i>
                                            Reject Photo

                                        </button>

                                    </form>

                                    @else

                                    {{-- Approve Photo --}}

                                    <form
                                        action="{{ route('admin.members.photos.approve', [
                                            'memberId' => $member->id,
                                            'photoId' => $photo->id
                                        ]) }}"
                                        method="POST"
                                        class="mb-2">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-success w-100"
                                            onclick="return confirm('Are you sure you want to approve this photo?')">

                                            <i class="bi bi-check-circle me-1"></i>
                                            Approve Photo

                                        </button>

                                    </form>

                                    @endif


                                    {{-- =================================================
                                    SET AS PROFILE PHOTO
                                ================================================== --}}

                                    @if($member->photo === $photo->photo)

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-success w-100"
                                        disabled>

                                        <i class="bi bi-person-check me-1"></i>
                                        Current Profile Photo

                                    </button>

                                    @else

                                    <form
                                        action="{{ route('admin.members.photos.set-profile', [
                                            'memberId' => $member->id,
                                            'photoId' => $photo->id
                                        ]) }}"
                                        method="POST">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-primary w-100"
                                            onclick="return confirm('Set this photo as the profile photo?')">

                                            <i class="bi bi-person-check me-1"></i>
                                            Set as Profile

                                        </button>

                                    </form>

                                    @endif

                                    {{-- =================================================
    DELETE PHOTO
================================================== --}}

                                    @if($member->photo !== $photo->photo)

                                    <form
                                        action="{{ route('admin.members.photos.delete', [
            'memberId' => $member->id,
            'photoId' => $photo->id
        ]) }}"
                                        method="POST"
                                        class="mt-2">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger w-100"
                                            onclick="return confirm('Are you sure you want to permanently delete this photo?')">

                                            <i class="bi bi-trash me-1"></i>
                                            Delete Photo

                                        </button>

                                    </form>

                                    @else

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary w-100 mt-2"
                                        disabled>

                                        <i class="bi bi-lock me-1"></i>
                                        Cannot Delete Profile Photo

                                    </button>

                                    @endif


                                </div>

                            </div>

                        </div>

                        @endforeach

                    </div>


                    @else

                    {{-- =================================================
                NO PHOTOS
            ================================================== --}}

                    <div class="text-center py-5 text-muted">

                        <i class="bi bi-images fs-1"></i>

                        <h6 class="mt-3">
                            No gallery photos found
                        </h6>

                        <p class="mb-0">
                            This member has not uploaded any gallery photos.
                        </p>

                    </div>

                    @endif

                </div>

            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">

                {{-- Activate / Deactivate --}}
                @if($member->is_active)

                <form method="POST"
                    action="{{ route('admin.members.toggle-status', $member->id) }}">
                    @csrf

                    <button type="submit" class="btn btn-warning">
                        Deactivate
                    </button>
                </form>

                @else

                <form method="POST"
                    action="{{ route('admin.members.toggle-status', $member->id) }}">
                    @csrf

                    <button type="submit" class="btn btn-success">
                        Activate
                    </button>
                </form>

                @endif


                {{-- Trust --}}
                <form method="POST"
                    action="{{ route('admin.members.toggle-trusted', $member->id) }}">
                    @csrf

                    <button type="submit" class="btn btn-outline-primary">

                        @if(strtolower((string) $member->is_trusted) === 'yes')
                        Remove Trusted
                        @else
                        Mark Trusted
                        @endif

                    </button>
                </form>


                {{-- Hide / Show --}}
                <form method="POST"
                    action="{{ route('admin.members.toggle-visibility', $member->id) }}">
                    @csrf

                    <button type="submit" class="btn btn-outline-secondary">

                        @if(!empty($member->profile_hide) &&
                        strtolower((string) $member->profile_hide) === 'yes')

                        Show Profile

                        @else

                        Hide Profile

                        @endif

                    </button>
                </form>


                {{-- Promote --}}
                <form method="POST"
                    action="{{ route('admin.members.toggle-promoted', $member->id) }}">
                    @csrf

                    <button type="submit" class="btn btn-outline-success">

                        @if(strtolower((string) $member->promoted) === 'yes')
                        Remove Promotion
                        @else
                        Promote Profile
                        @endif

                    </button>
                </form>

            </div>

        </div>

        @endsection