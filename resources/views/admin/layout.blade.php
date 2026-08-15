<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Panel')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('admin/css/admin.css') }}">
    <script src="{{asset('admin/js/admin.js')}}"></script>

    @stack('styles')

</head>
@php
$currentSite = app(\App\Services\SiteManager::class)->current();
@endphp

<body>

    <div class="admin-wrapper d-flex">

        {{-- Sidebar --}}
        <aside class="sidebar">

            <div class="brand">
                {{$currentSite->name}} Admin
            </div>


            <nav class="mt-3">

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>

                {{-- Masters --}}
                <div class="nav-group {{ request()->routeIs('admin.educations.*')
                    || request()->routeIs('admin.religions.*')
                    || request()->routeIs('admin.casts.*')
                    || request()->routeIs('admin.occupations.*')
                    || request()->routeIs('admin.employers.*')
                    || request()->routeIs('admin.marital-status.*')
                    || request()->routeIs('admin.family-status.*')
                    || request()->routeIs('admin.heights.*')
                    || request()->routeIs('admin.mother-tongues.*')
                    || request()->routeIs('admin.countries.*')
                    || request()->routeIs('admin.states.*')
                    || request()->routeIs('admin.cities.*')
                    ? 'is-open' : '' }}">

                    <button
                        type="button"
                        class="nav-group-toggle
        {{ request()->routeIs('admin.educations.*')
            || request()->routeIs('admin.religions.*')
            || request()->routeIs('admin.casts.*')
            || request()->routeIs('admin.occupations.*')
            ? 'active'
            : '' }}"
                        aria-expanded="{{ request()->routeIs('admin.educations.*')
                            || request()->routeIs('admin.religions.*')
                            || request()->routeIs('admin.casts.*')
                            || request()->routeIs('admin.occupations.*')
                            || request()->routeIs('admin.employers.*')
                            || request()->routeIs('admin.marital-status.*')
                            || request()->routeIs('admin.family-status.*')
                            || request()->routeIs('admin.heights.*')
                            || request()->routeIs('admin.mother-tongues.*')
                            || request()->routeIs('admin.countries.*')
                            || request()->routeIs('admin.states.*')
                            || request()->routeIs('admin.cities.*') ? 'true' : 'false' }}">

                        <i class="bi bi-database me-2"></i>
                        Masters

                        <i class="bi bi-chevron-down ms-auto"></i>

                    </button>


                    <div class="nav-submenu">

                        <a
                            href="{{ route('admin.religions.index') }}"
                            class="{{ request()->routeIs('admin.religions.*') ? 'active' : '' }}">

                            <i class="bi bi-heart me-2"></i>
                            Religions

                        </a>


                        <a
                            href="{{ route('admin.casts.index') }}"
                            class="{{ request()->routeIs('admin.casts.*') ? 'active' : '' }}">

                            <i class="bi bi-people me-2"></i>
                            Casts

                        </a>


                        <a
                            href="{{ route('admin.educations.index') }}"
                            class="{{ request()->routeIs('admin.educations.*') ? 'active' : '' }}">

                            <i class="bi bi-mortarboard me-2"></i>
                            Education

                        </a>


                        <a
                            href="{{ route('admin.occupations.index') }}"
                            class="{{ request()->routeIs('admin.occupations.*') ? 'active' : '' }}">

                            <i class="bi bi-briefcase me-2"></i>
                            Occupations

                        </a>

                        <a
                            href="{{ route('admin.employers.index') }}"
                            class="{{ request()->routeIs('admin.employers.*') ? 'active' : '' }}">

                            <i class="bi bi-buildings me-2"></i>
                            Employer

                        </a>

                        <a
                            href="{{ route('admin.marital-status.index') }}"
                            class="{{ request()->routeIs('admin.marital-status.*') ? 'active' : '' }}">

                            <i class="bi bi-heartbreak me-2"></i>
                            Marital Status

                        </a>

                        <a
                            href="{{ route('admin.family-status.index') }}"
                            class="{{ request()->routeIs('admin.family-status.*') ? 'active' : '' }}">

                            <i class="bi bi-house-heart me-2"></i>
                            Family Status

                        </a>

                        <a
                            href="{{ route('admin.heights.index') }}"
                            class="{{ request()->routeIs('admin.heights.*') ? 'active' : '' }}">

                            <i class="bi bi-rulers me-2"></i>
                            Heights

                        </a>

                        <a
                            href="{{ route('admin.mother-tongues.index') }}"
                            class="{{ request()->routeIs('admin.mother-tongues.*') ? 'active' : '' }}">

                            <i class="bi bi-translate me-2"></i>
                            Mother Tongues

                        </a>

                        <a
                            href="{{ route('admin.countries.index') }}"
                            class="{{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">

                            <i class="bi bi-globe2 me-2"></i>
                            Countries

                        </a>

                        <a
                            href="{{ route('admin.states.index') }}"
                            class="{{ request()->routeIs('admin.states.*') ? 'active' : '' }}">

                            <i class="bi bi-map me-2"></i>
                            States

                        </a>

                        <a
                            href="{{ route('admin.cities.index') }}"
                            class="{{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">

                            <i class="bi bi-geo-alt me-2"></i>
                            Cities

                        </a>

                    </div>

                </div>

                <a
                    href="{{ route('admin.members.index') }}"
                    class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i>
                    Members
                </a>


                <div class="nav-dropdown {{ request()->routeIs('admin.membership-types.*') || request()->routeIs('admin.membership-plans.*') ? 'is-open' : '' }}">

                    <button
                        type="button"
                        class="nav-dropdown-toggle"
                        aria-expanded="{{ request()->routeIs('admin.membership-types.*') || request()->routeIs('admin.membership-plans.*') ? 'true' : 'false' }}">
                        <i class="bi bi-award me-2"></i>
                        Membership
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </button>

                    <div class="nav-dropdown-menu">

                        <a
                            href="{{ route('admin.membership-types.index') }}"
                            class="{{ request()->routeIs('admin.membership-types.*') ? 'active' : '' }}">

                            <i class="bi bi-layers"></i>
                            Membership Types

                        </a>


                        <a
                            href="{{ route('admin.membership-plans.index') }}"
                            class="{{ request()->routeIs('admin.membership-plans.*') ? 'active' : '' }}">

                            <i class="bi bi-credit-card"></i>
                            Membership Plans

                        </a>

                    </div>

                </div>

                <a
                    href="{{ route('admin.pages.index') }}"
                    class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">

                    <i class="bi bi-file-earmark-text"></i>

                    Pages

                </a>

                <a href="{{ route('admin.user-ratings.index') }}"
                    class="{{ request()->routeIs('admin.user-ratings.*') ? 'active' : '' }}">

                    <i class="bi bi-star"></i>

                    <span>User Ratings</span>

                </a>

                {{-- Success Stories --}}
                <a href="{{ route('admin.success-stories.index') }}"
                    class="{{ request()->routeIs('admin.success-stories.*') ? 'active' : '' }}">
                    <i class="bi bi-heart-fill"></i>
                    <span>Success Stories</span>
                </a>


                <a href="#">
                    <i class="bi bi-person-badge me-2"></i>
                    Agents
                </a>


                <a href="#">
                    <i class="bi bi-bar-chart-line me-2"></i>
                    Reports
                </a>


                <a href="#">
                    <i class="bi bi-journal-text me-2"></i>
                    Content
                </a>


                <a href="#">
                    <i class="bi bi-gear me-2"></i>
                    Settings
                </a>

            </nav>


            <div class="mt-4">

                <form
                    method="POST"
                    action="{{ route('admin.logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-link text-danger text-decoration-none px-3">
                        Logout
                    </button>

                </form>

            </div>

        </aside>


        {{-- Main --}}
        <div class="main-wrapper">

            {{-- Topbar --}}
            <header class="topbar">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            @yield('page-title', 'Admin Dashboard')
                        </strong>

                    </div>


                    <div class="d-flex align-items-center gap-3">

                        @php
                        $currentSite = app(\App\Services\SiteManager::class)->current();
                        @endphp

                        @if($currentSite)

                        <div class="text-end">

                            <div class="fw-semibold">
                                {{ $currentSite->name }}
                            </div>

                            <small class="text-muted">
                                {{ $currentSite->database_name }}
                            </small>

                        </div>

                        @endif


                        <a
                            href="{{ route('admin.site.select') }}"
                            class="btn btn-sm btn-outline-primary">
                            Switch Site
                        </a>
                        <button type="button" id="themeToggle" class="btn btn-light theme-toggle" aria-label="Toggle dark mode">
                            <i class="bi bi-moon"></i>
                        </button>

                        <span>
                            {{ auth('admin')->user()->name }}
                        </span>

                    </div>

                </div>

            </header>


            {{-- Page Content --}}
            <main class="content">

                @yield('content')

            </main>

        </div>

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.nav-group-toggle, .nav-dropdown-toggle')
            .forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const menu = this.closest('.nav-group, .nav-dropdown');
                    const isOpen = menu.classList.toggle('is-open');

                    this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });
    </script>

    @stack('scripts')

</body>

</html>