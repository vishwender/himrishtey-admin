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

    <style>
        :root {
            --app-primary: #6d4aff;
            --app-primary-dark: #5132d5;
            --app-ink: #20243a;
            --app-muted: #78809a;
            --app-surface: #ffffff;
            --app-canvas: #f6f7fc;
            --app-border: #e9eaf2;
        }

        body {
            min-width: 320px;
            background: var(--app-canvas);
            color: var(--app-ink);
            font-family: 'DM Sans', sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .topbar strong {
            font-family: 'Outfit', sans-serif;
            color: var(--app-ink);
        }

        .admin-wrapper { min-height: 100vh; }

        .sidebar {
            position: sticky;
            top: 0;
            width: 268px;
            height: 100vh;
            flex: 0 0 268px;
            overflow-y: auto;
            background: linear-gradient(180deg, #242a4d 0%, #171b36 100%);
            color: #fff;
            box-shadow: 10px 0 30px rgba(27, 31, 66, .08);
        }

        .sidebar .brand {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: 24px 22px;
            background: rgba(36, 42, 77, .96);
            border-bottom: 1px solid rgba(255, 255, 255, .09);
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .sidebar .brand::before {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            margin-right: 10px;
            border-radius: 10px;
            background: linear-gradient(135deg, #9b7cff, #6d4aff);
            content: '♥';
            font-family: Arial, sans-serif;
            font-size: 15px;
        }

        .sidebar nav { padding: 10px 12px; }

        .sidebar a,
        .nav-group-toggle,
        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            width: 100%;
            margin: 2px 0;
            padding: 11px 12px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #bfc5dd;
            cursor: pointer;
            font: 500 .9rem 'DM Sans', sans-serif;
            text-align: left;
            text-decoration: none;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }

        .sidebar a > .bi:not(.bi-chevron-down),
        .nav-group-toggle > .bi:not(.bi-chevron-down),
        .nav-dropdown-toggle > .bi:not(.bi-chevron-down) {
            width: 22px;
            margin-right: 8px !important;
            font-size: 1rem;
            text-align: center;
        }

        .sidebar a:hover,
        .sidebar a.active,
        .nav-group-toggle:hover,
        .nav-group-toggle.active,
        .nav-dropdown-toggle:hover,
        .nav-dropdown-toggle.active {
            background: rgba(151, 124, 255, .16);
            color: #fff;
        }

        .sidebar a.active { box-shadow: inset 3px 0 0 #a78bfa; }

        .nav-group-toggle .bi-chevron-down,
        .nav-dropdown-toggle .bi-chevron-down { transition: transform .2s ease; }

        .nav-group.is-open .bi-chevron-down,
        .nav-dropdown.is-open .bi-chevron-down { transform: rotate(180deg); }

        .nav-submenu, .nav-dropdown-menu { display: none; }
        .nav-group.is-open .nav-submenu,
        .nav-dropdown.is-open .nav-dropdown-menu { display: block; }

        .nav-submenu a, .nav-dropdown-menu a {
            padding-left: 28px;
            color: #aeb5d2;
            font-size: .84rem;
        }

        .sidebar .mt-4 { padding: 8px 12px 22px; }
        .sidebar .btn-link { width: 100%; border-radius: 10px; text-align: left; }

        .main-wrapper { min-width: 0; flex: 1; }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 14px 32px;
            background: rgba(255, 255, 255, .88);
            border-bottom: 1px solid var(--app-border);
            backdrop-filter: blur(16px);
        }

        .topbar strong { font-size: 1.05rem; }
        .content { padding: 32px; }

        .card {
            border: 1px solid var(--app-border) !important;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(32, 36, 58, .045) !important;
        }

        .card-header { border-color: var(--app-border); }
        .card-body { padding: 1.5rem; }

        .btn {
            border-radius: 9px;
            font-weight: 600;
            padding: .56rem 1rem;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            border-color: var(--app-primary);
            background: linear-gradient(135deg, #8063ff, #6040ed);
            box-shadow: 0 6px 14px rgba(96, 64, 237, .2);
        }
        .btn-primary:hover, .btn-primary:focus {
            border-color: var(--app-primary-dark);
            background: linear-gradient(135deg, #7151f8, #5132d5);
        }

        .form-control, .form-select, .input-group-text {
            min-height: 42px;
            border-color: #dfe2ec;
            border-radius: 9px;
            color: var(--app-ink);
        }
        .input-group > .form-control { border-radius: 0 9px 9px 0; }
        .input-group > .input-group-text { border-radius: 9px 0 0 9px; background: #f8f8fc; }
        .form-control:focus, .form-select:focus {
            border-color: #9a86ff;
            box-shadow: 0 0 0 .22rem rgba(109, 74, 255, .12);
        }
        .form-label { margin-bottom: .45rem; font-size: .875rem; font-weight: 600; }

        .table { --bs-table-bg: transparent; --bs-table-hover-bg: #fafaff; }
        .table > :not(caption) > * > * { padding: .9rem 1rem; border-color: var(--app-border); }
        .table thead th { color: #68708a; font-size: .75rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
        .badge { border-radius: 7px; font-weight: 600; }
        .alert { border: 0; border-radius: 12px; }
        .pagination { margin-bottom: 0; }
        .pagination svg { width: 1rem !important; height: 1rem !important; }
        .page-link { border-color: var(--app-border); color: var(--app-primary); }
        .page-item.active .page-link { border-color: var(--app-primary); background-color: var(--app-primary); }

        @media (max-width: 991.98px) {
            .sidebar { width: 220px; flex-basis: 220px; }
            .topbar, .content { padding-left: 20px; padding-right: 20px; }
        }

        @media (max-width: 767.98px) {
            .admin-wrapper { display: block !important; }
            .sidebar { position: relative; width: 100%; height: auto; min-height: 0; overflow: visible; }
            .sidebar .brand { position: relative; }
            .sidebar nav { display: flex; overflow-x: auto; padding: 8px; }
            .sidebar nav > a, .sidebar nav > div { flex: 0 0 auto; }
            .sidebar .mt-4 { display: none; }
            .topbar { position: relative; padding: 14px 16px; }
            .topbar .d-flex { gap: 12px; align-items: flex-start !important; flex-direction: column; }
            .content { padding: 20px 16px; }
        }
    </style>

    @stack('styles')

</head>

<body>

    <div class="admin-wrapper d-flex">

        {{-- Sidebar --}}
        <aside class="sidebar">

            <div class="brand">
                Matrimonial Admin
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


                <a href="#">
                    <i class="bi bi-credit-card me-2"></i>
                    Payments
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
            .forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    const menu = this.closest('.nav-group, .nav-dropdown');
                    const isOpen = menu.classList.toggle('is-open');

                    this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });
    </script>

    @stack('scripts')

</body>

</html>
