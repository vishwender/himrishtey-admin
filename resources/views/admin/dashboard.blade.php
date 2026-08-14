@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dashboard-site-card { background: linear-gradient(110deg, #fff 0%, #f5f2ff 100%); }
    .dashboard-stat { overflow: hidden; position: relative; }
    .dashboard-stat .stat-icon { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 14px; font-size: 1.25rem; }
    .dashboard-stat .stat-icon.members { background: #eeeaff; color: #6d4aff; }
    .dashboard-stat .stat-icon.active { background: #e3f9ed; color: #18864b; }
    .dashboard-stat .stat-icon.inactive { background: #fff2df; color: #ca7515; }
    .dashboard-stat .stat-icon.payments { background: #e4f4ff; color: #1672bd; }
    .dashboard-stat h3 { font-size: 2rem; font-weight: 700; letter-spacing: -.04em; }
</style>
@endpush

@section('content')

@php
$currentSite = app(\App\Services\SiteManager::class)->current();
@endphp

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">Dashboard</h1>

            <p class="text-muted mb-0">
                Overview of the selected matrimonial site.
            </p>
        </div>

        <div>
            <a
                href="{{ route('admin.site.select') }}"
                class="btn btn-outline-primary">
                Switch Site
            </a>
        </div>

    </div>


    {{-- Current Site --}}
    @if($currentSite)

    <div class="card border-0 shadow-sm mb-4 dashboard-site-card">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Current Site
                    </small>

                    <h5 class="mb-0">
                        {{ $currentSite->name }}
                    </h5>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Site Code
                    </small>

                    <h6 class="mb-0">
                        {{ $currentSite->code }}
                    </h6>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Database
                    </small>

                    <h6 class="mb-0">
                        {{ $currentSite->database_name }}
                    </h6>

                </div>

            </div>

        </div>

    </div>

    @else

    <div class="alert alert-warning">

        <strong>No site selected.</strong>

        <a
            href="{{ route('admin.site.select') }}"
            class="alert-link">
            Select a site
        </a>

    </div>

    @endif


    {{-- Dashboard Statistics --}}
    <div class="row g-4">

        {{-- Members --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100 dashboard-stat">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="text-muted">Members</div>
                        <span class="stat-icon members"><i class="bi bi-people"></i></span>
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['members']) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Active Members --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100 dashboard-stat">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="text-muted">Active Members</div>
                        <span class="stat-icon active"><i class="bi bi-person-check"></i></span>
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['active_members']) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Pending Profiles --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100 dashboard-stat">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="text-muted">Inactive Profiles</div>
                        <span class="stat-icon inactive"><i class="bi bi-person-dash"></i></span>
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['inactive_members']) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Payments --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100 dashboard-stat">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="text-muted">Payments</div>
                        <span class="stat-icon payments"><i class="bi bi-credit-card"></i></span>
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['payments']) }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
