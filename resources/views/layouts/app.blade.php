{{--
<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
--}}


    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="zxx">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/rangeslider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/google-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jsvectormap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightpick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Title -->
    <title>@yield('title', 'Trezo - Dashboard')</title>

    @livewireStyles
</head>
<body class="boxed-size">
<!-- Preloader -->
@include('layouts.dashboard.partials.preloader')

<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <!-- Sidebar -->
        @include('layouts.dashboard.partials.sidebar')

        <!-- Header -->
        @include('layouts.dashboard.partials.header')

        <!-- Main Content Area -->
        <div class="main-content-container overflow-hidden">
            <!-- Breadcrumb dhe titull -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h3 class="mb-0">@yield('page-title', 'Dashboard')</h3>

                @if(View::hasSection('breadcrumb'))
                    @yield('breadcrumb')
                @else
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb align-items-center mb-0 lh-1">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"
                                   class="d-flex align-items-center text-decoration-none">
                                    <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                                    <span class="text-secondary fw-medium hover">Dashboard</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <span class="fw-medium">@yield('page-title', 'Current Page')</span>
                            </li>
                        </ol>
                    </nav>
                @endif
            </div>

            <!-- Content -->
            {{ $slot }}
        </div>

        <div class="flex-grow-1"></div>

        <!-- Footer -->
        @include('layouts.dashboard.partials.footer')
    </div>
</div>

<!-- Theme Settings -->
@include('layouts.dashboard.partials.theme-settings')

<!-- Scripts -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/dragdrop.js') }}"></script>
<script src="{{ asset('assets/js/rangeslider.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/prism.js') }}"></script>
<script src="{{ asset('assets/js/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/echarts.min.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/fullcalendar.main.js') }}"></script>
<script src="{{ asset('assets/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/js/world-merc.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/lightpick.js') }}"></script>
<script src="{{ asset('assets/js/custom/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/custom/echarts.js') }}"></script>
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>

@livewireScripts
</body>
</html>
