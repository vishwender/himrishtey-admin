@extends('admin.layout')

@section('title', 'Delete Profile Requests')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
        HEADER
    ================================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Delete Profile Requests
            </h4>

            <p class="text-muted mb-0">
                Review profile deletion requests submitted by members.
            </p>
        </div>

    </div>


    {{-- ================================================================
        SUMMARY
    ================================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <div
                        class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary"
                        style="width:48px;height:48px;">

                        <i class="bi bi-person-x fs-5"></i>

                    </div>

                    <div>

                        <div class="text-muted small">
                            Total Requests
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ number_format($totalRequests) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <div
                        class="d-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-warning"
                        style="width:48px;height:48px;">

                        <i class="bi bi-clock-history fs-5"></i>

                    </div>

                    <div>

                        <div class="text-muted small">
                            Pending
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ number_format($pendingRequests) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <div
                        class="d-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success"
                        style="width:48px;height:48px;">

                        <i class="bi bi-check-circle fs-5"></i>

                    </div>

                    <div>

                        <div class="text-muted small">
                            Processed
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ number_format($processedRequests) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        FILTERS
    ================================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.delete-profile-requests.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-lg-6 col-md-6">

                        <label class="form-label fw-semibold">
                            Search Member
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Profile ID, name, email or mobile">

                    </div>


                    {{-- Status --}}

                    <div class="col-lg-3 col-md-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="0"
                                {{ request('status') === '0' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option
                                value="1"
                                {{ request('status') === '1' ? 'selected' : '' }}>
                                Accepted
                            </option>

                            <option
                                value="2"
                                {{ request('status') === '2' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-lg-3 col-md-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-grow-1">

                                <i class="bi bi-search me-1"></i>
                                Filter

                            </button>

                            <a
                                href="{{ route('admin.delete-profile-requests.index') }}"
                                class="btn btn-outline-secondary">

                                <i class="bi bi-x-lg"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
        REQUESTS TABLE
    ================================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="fw-bold mb-0">
                    Profile Deletion Requests
                </h6>

                <span class="text-muted small">
                    {{ $requests->total() }} request(s)
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th style="width:70px;">
                            #
                        </th>

                        <th>
                            Member
                        </th>

                        <th>
                            Profile ID
                        </th>

                        <th>
                            Reason
                        </th>

                        <th>
                            Requested On
                        </th>

                        <th>
                            Requests
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($requests as $requestItem)

                    @php
                    $member = $requestItem->member;
                    @endphp

                    <tr>

                        {{-- ID --}}

                        <td class="text-muted">
                            {{ $requestItem->id }}
                        </td>


                        {{-- Member --}}

                        <td>

                            @if($member)

                            <div class="fw-semibold">
                                {{ $member->full_name }}
                            </div>

                            <div class="small text-muted">

                                @if(!empty($member->mobile_number))
                                {{ $member->mobile_number }}
                                @endif

                                @if(!empty($member->email))
                                <span class="mx-1">•</span>
                                {{ $member->email }}
                                @endif

                            </div>

                            @else

                            <span class="text-danger">
                                Member not found
                            </span>

                            <div class="small text-muted">
                                User ID: {{ $requestItem->user_id }}
                            </div>

                            @endif

                        </td>


                        {{-- Profile ID --}}

                        <td>

                            @if($member)

                            <span class="badge bg-light text-dark border">
                                {{ $member->profile_id }}
                            </span>

                            @else

                            —

                            @endif

                        </td>


                        {{-- Reason --}}

                        <td style="max-width:350px;">

                            @if(!empty($requestItem->reason))

                            <div
                                class="text-wrap"
                                title="{{ $requestItem->reason }}">

                                {{ \Illuminate\Support\Str::limit(
                                            $requestItem->reason,
                                            100
                                        ) }}

                            </div>

                            @else

                            <span class="text-muted">
                                No reason provided
                            </span>

                            @endif

                        </td>


                        {{-- Date --}}

                        <td>

                            @if(!empty($requestItem->date))

                            {{ $requestItem->date }}

                            @else

                            —

                            @endif

                        </td>

                        <td>

                            @if($requestItem->request_count > 1)

                            <span class="badge bg-danger-subtle text-danger-emphasis">

                                <i class="bi bi-exclamation-circle me-1"></i>

                                {{ $requestItem->request_count }} Requests

                            </span>

                            @else

                            <span class="badge bg-light text-dark border">

                                1 Request

                            </span>

                            @endif

                        </td>


                        {{-- Status --}}

                        <td>

                            @if((int) $requestItem->status === 0)

                            <span class="badge bg-warning-subtle text-warning-emphasis">

                                <i class="bi bi-clock me-1"></i>
                                Pending

                            </span>

                            @elseif((int) $requestItem->status === 1)

                            <span class="badge bg-success-subtle text-success-emphasis">

                                <i class="bi bi-check-circle me-1"></i>
                                Accepted

                            </span>

                            @elseif((int) $requestItem->status === 2)

                            <span class="badge bg-danger-subtle text-danger-emphasis">

                                <i class="bi bi-x-circle me-1"></i>
                                Rejected

                            </span>

                            @endif

                        </td>


                        {{-- Actions --}}

                        <td class="text-end">

                            @if((int) $requestItem->status === 0)

                            <div class="d-flex justify-content-end gap-2">

                                {{-- Accept --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                    'admin.delete-profile-requests.accept',
                    $requestItem->id
                ) }}"
                                    onsubmit="return confirm(
                    'Are you sure you want to accept this profile deletion request?'
                );">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-success">

                                        <i class="bi bi-check-lg me-1"></i>
                                        Accept

                                    </button>

                                </form>


                                {{-- Reject --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                    'admin.delete-profile-requests.reject',
                    $requestItem->id
                ) }}"
                                    onsubmit="return confirm(
                    'Are you sure you want to reject this profile deletion request?'
                );">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger">

                                        <i class="bi bi-x-lg me-1"></i>
                                        Reject

                                    </button>

                                </form>

                            </div>

                            @elseif((int) $requestItem->status === 1)

                            <span class="text-success fw-semibold">
                                <i class="bi bi-check-circle me-1"></i>
                                Accepted
                            </span>

                            @elseif((int) $requestItem->status === 2)

                            <span class="text-danger fw-semibold">
                                <i class="bi bi-x-circle me-1"></i>
                                Rejected
                            </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-5">

                            <div class="text-muted">

                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                No profile deletion requests found.

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        @if($requests->hasPages())

        <div class="card-footer bg-white">

            {{ $requests->links() }}

        </div>

        @endif

    </div>

</div>

@endsection