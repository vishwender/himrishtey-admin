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

    <style>
        body {
            background: #f5f6fa;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1f2937;
            color: #fff;
        }

        .sidebar .brand {
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 20px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #374151;
            color: #fff;
        }

        .main-wrapper {
            flex: 1;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 20px;
        }

        .content {
            padding: 25px;
        }

        .pagination svg {
            width: 1rem !important;
            height: 1rem !important;
        }

        .pagination {
            margin-bottom: 0;
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
                    Dashboard
                </a>

                {{-- Masters --}}
                <div class="nav-group">

                    <a
                        href="#"
                        class="nav-group-toggle
        {{ request()->routeIs('admin.educations.*')
            || request()->routeIs('admin.religions.*')
            || request()->routeIs('admin.casts.*')
            || request()->routeIs('admin.occupations.*')
            ? 'active'
            : '' }}">

                        <i class="bi bi-database me-2"></i>
                        Masters

                        <i class="bi bi-chevron-down ms-auto"></i>

                    </a>


                    <div class="nav-submenu">

                        <a
                            href="{{ route('admin.religions.index') }}"
                            class="{{ request()->routeIs('admin.religions.*') ? 'active' : '' }}">

                            <i class="bi bi-circle me-2"></i>
                            Religions

                        </a>


                        <a
                            href="{{ route('admin.casts.index') }}"
                            class="{{ request()->routeIs('admin.casts.*') ? 'active' : '' }}">

                            <i class="bi bi-circle me-2"></i>
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

                    </div>

                </div>

                <a
                    href="{{ route('admin.members.index') }}"
                    class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    Members
                </a>


                <a href="#">
                    Membership
                </a>


                <a href="#">
                    Payments
                </a>


                <a href="#">
                    Agents
                </a>


                <a href="#">
                    Reports
                </a>


                <a href="#">
                    Content
                </a>


                <a href="#">
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

    @stack('scripts')

</body>

</html>