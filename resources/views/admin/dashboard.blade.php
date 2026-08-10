@extends('admin.layout')

@section('title', 'Dashboard')

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

    <div class="card border-0 shadow-sm mb-4">

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

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted mb-2">
                        Members
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['members']) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Active Members --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted mb-2">
                        Active Members
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['active_members']) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Pending Profiles --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted mb-2">
                        Inactive Profiles
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['inactive_members']) }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- Payments --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted mb-2">
                        Payments
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