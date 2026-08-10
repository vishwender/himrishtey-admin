@extends('admin.layout')

@section('title', 'Members')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">Members</h1>

            <p class="text-muted mb-0">
                Manage members of the selected site.
            </p>
        </div>

    </div>


    {{-- Search / Filter --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.members.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Search Member
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Profile ID, name, email or mobile">

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option
                                value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- Trusted --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Trusted
                        </label>

                        <select
                            name="trusted"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="yes"
                                {{ request('trusted') === 'yes' ? 'selected' : '' }}>

                                Trusted

                            </option>

                            <option
                                value="no"
                                {{ request('trusted') === 'no' ? 'selected' : '' }}>

                                Not Trusted

                            </option>

                        </select>

                    </div>


                    {{-- Promoted --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Promoted
                        </label>

                        <select
                            name="promoted"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="yes"
                                {{ request('promoted') === 'yes' ? 'selected' : '' }}>

                                Promoted

                            </option>

                            <option
                                value="no"
                                {{ request('promoted') === 'no' ? 'selected' : '' }}>

                                Not Promoted

                            </option>

                        </select>

                    </div>


                    {{-- Visibility --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Visibility
                        </label>

                        <select
                            name="visibility"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="visible"
                                {{ request('visibility') === 'visible' ? 'selected' : '' }}>

                                Visible

                            </option>

                            <option
                                value="hidden"
                                {{ request('visibility') === 'hidden' ? 'selected' : '' }}>

                                Hidden

                            </option>

                        </select>

                    </div>

                    {{-- Membership Plan --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Membership Plan
                        </label>

                        <select
                            name="plan_id"
                            class="form-select">

                            <option value="">
                                All Plans
                            </option>

                            <option
                                value="none"
                                {{ request('plan_id') === 'none' ? 'selected' : '' }}>

                                No Plan

                            </option>

                            @foreach($plans as $plan)

                            <option
                                value="{{ $plan->id }}"
                                {{ request('plan_id') == $plan->id ? 'selected' : '' }}>

                                {{ $plan->plan_name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Sort --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Sort By
                        </label>

                        <select
                            name="sort"
                            class="form-select">

                            <option
                                value="newest"
                                {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>
                                Newest First
                            </option>

                            <option
                                value="oldest"
                                {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                Oldest First
                            </option>

                            <option
                                value="name_asc"
                                {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                                Name A-Z
                            </option>

                            <option
                                value="name_desc"
                                {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                                Name Z-A
                            </option>

                            <option
                                value="profile_asc"
                                {{ request('sort') === 'profile_asc' ? 'selected' : '' }}>
                                Profile ID A-Z
                            </option>

                            <option
                                value="profile_desc"
                                {{ request('sort') === 'profile_desc' ? 'selected' : '' }}>
                                Profile ID Z-A
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="bi bi-funnel me-1"></i>
                                Apply Filters

                            </button>


                            <a
                                href="{{ route('admin.members.index') }}"
                                class="btn btn-outline-secondary">

                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- =========================================================
    Active Filters
========================================================= --}}

    @php
    $hasFilters =
    request()->filled('search') ||
    request()->filled('status') ||
    request()->filled('trusted') ||
    request()->filled('promoted') ||
    request()->filled('visibility') ||
    request()->filled('plan_id') ||
    request()->filled('sort');
    @endphp


    @if($hasFilters)

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body py-3">

            <div class="d-flex flex-wrap align-items-center gap-2">

                <span class="fw-semibold text-muted me-1">
                    <i class="bi bi-funnel me-1"></i>
                    Active Filters:
                </span>


                {{-- Search --}}

                @if(request()->filled('search'))

                <span class="badge bg-primary d-flex align-items-center gap-1">

                    Search:
                    {{ request('search') }}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                                'search' => null,
                                'page' => null
                            ]) }}"
                        class="text-white text-decoration-none ms-1"
                        title="Remove search">

                        &times;

                    </a>

                </span>

                @endif


                {{-- Status --}}

                @if(request()->filled('status'))

                <span class="badge bg-secondary d-flex align-items-center gap-1">

                    Status:
                    {{ request('status') === 'active' ? 'Active' : 'Inactive' }}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                                'status' => null,
                                'page' => null
                            ]) }}"
                        class="text-white text-decoration-none ms-1"
                        title="Remove status">

                        &times;

                    </a>

                </span>

                @endif


                {{-- Trusted --}}

                @if(request()->filled('trusted'))

                <span class="badge bg-info text-dark d-flex align-items-center gap-1">

                    Trusted:
                    {{ request('trusted') === 'yes' ? 'Yes' : 'No' }}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                                'trusted' => null,
                                'page' => null
                            ]) }}"
                        class="text-dark text-decoration-none ms-1"
                        title="Remove trusted filter">

                        &times;

                    </a>

                </span>

                @endif


                {{-- Promoted --}}

                @if(request()->filled('promoted'))

                <span class="badge bg-warning text-dark d-flex align-items-center gap-1">

                    Promoted:
                    {{ request('promoted') === 'yes' ? 'Yes' : 'No' }}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                                'promoted' => null,
                                'page' => null
                            ]) }}"
                        class="text-dark text-decoration-none ms-1"
                        title="Remove promoted filter">

                        &times;

                    </a>

                </span>

                @endif


                {{-- Visibility --}}

                @if(request()->filled('visibility'))

                <span class="badge bg-dark d-flex align-items-center gap-1">

                    Visibility:
                    {{ request('visibility') === 'visible' ? 'Visible' : 'Hidden' }}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                                'visibility' => null,
                                'page' => null
                            ]) }}"
                        class="text-white text-decoration-none ms-1"
                        title="Remove visibility filter">

                        &times;

                    </a>

                </span>

                @endif


                {{-- Membership Plan --}}

                @if(request()->filled('plan_id'))

                @php
                $selectedPlan = null;

                if (request('plan_id') !== 'none') {
                $selectedPlan = $plans->firstWhere(
                'id',
                request('plan_id')
                );
                }
                @endphp


                <span class="badge bg-primary d-flex align-items-center gap-1">

                    Plan:

                    @if(request('plan_id') === 'none')

                    No Plan

                    @elseif($selectedPlan)

                    {{ $selectedPlan->plan_name }}

                    @else

                    Unknown Plan

                    @endif


                    <a
                        href="{{ request()->fullUrlWithQuery([
                                'plan_id' => null,
                                'page' => null
                            ]) }}"
                        class="text-white text-decoration-none ms-1"
                        title="Remove membership filter">

                        &times;

                    </a>

                </span>

                @endif


                {{-- Sort --}}

                @if(request()->filled('sort'))

                @php
                $sortLabels = [
                'newest' => 'Newest First',
                'oldest' => 'Oldest First',
                'name_asc' => 'Name A-Z',
                'name_desc' => 'Name Z-A',
                'profile_asc' => 'Profile ID A-Z',
                'profile_desc' => 'Profile ID Z-A',
                ];

                $sortLabel = $sortLabels[request('sort')] ?? request('sort');
                @endphp


                <span class="badge bg-light text-dark border d-flex align-items-center gap-1">

                    Sort:
                    {{ $sortLabel }}

                    <a
                        href="{{ request()->fullUrlWithQuery([
                                'sort' => null,
                                'page' => null
                            ]) }}"
                        class="text-dark text-decoration-none ms-1"
                        title="Remove sorting">

                        &times;

                    </a>

                </span>

                @endif


                {{-- Clear All --}}

                <a
                    href="{{ route('admin.members.index') }}"
                    class="btn btn-sm btn-outline-danger ms-1">

                    <i class="bi bi-x-circle me-1"></i>
                    Clear All

                </a>

            </div>

        </div>

    </div>

    @endif


    {{-- Members Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Profile ID</th>

                            <th>Member</th>

                            <th>Mobile</th>

                            <th>Gender</th>

                            <th>Registration</th>

                            <th>Membership</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($members as $member)

                        <tr>

                            {{-- Profile ID --}}
                            <td>

                                <strong>
                                    {{ $member->profile_id }}
                                </strong>

                            </td>


                            {{-- Member --}}
                            <td>

                                <div>

                                    <strong>
                                        {{ $member->full_name }}
                                    </strong>

                                    <div class="small text-muted">
                                        {{ $member->email }}
                                    </div>

                                </div>

                            </td>


                            {{-- Mobile --}}
                            <td>
                                {{ $member->mobile_number }}
                            </td>


                            {{-- Gender --}}
                            <td>
                                {{ $member->gender }}
                            </td>


                            {{-- Registration --}}
                            <td>
                                {{ $member->registration_date }}
                            </td>

                            <td>
                                @if(!empty($member->membership_plan_name))

                                @php
                                $planName = strtolower($member->membership_plan_name);

                                $planClass = match (true) {
                                str_contains($planName, 'platinum'),
                                str_contains($planName, 'vip') => 'bg-danger',

                                str_contains($planName, 'premium') => 'bg-primary',

                                str_contains($planName, 'gold') => 'bg-warning text-dark',

                                str_contains($planName, 'silver') => 'bg-info text-dark',

                                str_contains($planName, 'basic'),
                                str_contains($planName, 'free') => 'bg-secondary',

                                default => 'bg-dark',
                                };
                                @endphp

                                <span class="badge {{ $planClass }}">
                                    {{ $member->membership_plan_name }}
                                </span>

                                @else

                                <span class="badge bg-light text-muted border">
                                    No Plan
                                </span>

                                @endif
                            </td>

                            {{-- Status --}}
                            <td>

                                @if($member->active === 'Yes')

                                <span class="badge bg-success">
                                    Active
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="text-end">

                                <a
                                    href="{{ route('admin.members.show', ['id' => $member->id,'return' => request()->fullUrl(),]) }}"
                                    class="btn btn-sm btn-primary">

                                    <i class="bi bi-eye me-1"></i>
                                    View

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5 text-muted">
                                No members found.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($members->hasPages())

        <div class="card-footer bg-white">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                <div class="text-muted small">

                    Showing
                    <strong>{{ $members->firstItem() }}</strong>
                    to
                    <strong>{{ $members->lastItem() }}</strong>
                    of
                    <strong>{{ $members->total() }}</strong>
                    members

                </div>

                <div>
                    {{ $members->onEachSide(1)->links() }}
                </div>

            </div>

        </div>

        @endif

    </div>

</div>

@endsection