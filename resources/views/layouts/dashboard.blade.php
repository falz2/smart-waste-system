@php
    $currentUser = Auth::user();
    $currentRole = $currentUser->role;
    $pendingCount = $currentUser->isAdmin()
        ? \App\Models\Report::where('status', 'pending')->count()
        : ($currentUser->isResident() ? $currentUser->reports()->where('status', 'pending')->count() : 0);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Overview') - Smart Waste</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen font-sans antialiased text-gray-800">
<div id="sidebarBackdrop" class="fixed inset-0 z-30 hidden bg-gray-950/50 lg:hidden"></div>

<aside id="appSidebar" class="fixed inset-y-0 left-0 z-40 flex h-dvh w-[270px] -translate-x-full flex-col bg-[#15382d] text-white transition-transform duration-200 lg:translate-x-0">
    <div class="border-b border-white/10 px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3" aria-label="Smart Waste dashboard">
            <span class="flex h-10 w-10 items-center justify-center rounded-md bg-[#d6ef91] text-[#173d2d]"><i class="fas fa-recycle" aria-hidden="true"></i></span>
            <span>
                <span class="block text-base font-bold leading-tight">Smart Waste</span>
                <span class="mt-1 block text-[11px] uppercase tracking-[0.16em] text-white/55">Kampala City</span>
            </span>
        </a>
    </div>

    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto px-3 py-5" aria-label="Main navigation">
        <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-white/40">
            {{ $currentUser->isAdmin() ? 'Operations' : ($currentUser->isCollector() ? 'Field work' : 'Account') }}
        </p>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fas fa-chart-pie w-5 text-center" aria-hidden="true"></i><span>Dashboard</span>
        </a>

        @if ($currentUser->isAdmin())
            <a href="{{ route('bins.index') }}" class="{{ request()->routeIs('bins.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fas fa-dumpster w-5 text-center" aria-hidden="true"></i><span>Bins</span>
            </a>
        @endif

        @if ($currentUser->isAdmin() || $currentUser->isResident())
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fas fa-flag w-5 text-center" aria-hidden="true"></i><span class="flex-1">{{ $currentUser->isAdmin() ? 'Reports' : 'My reports' }}</span>
                @if ($pendingCount > 0)
                    <span class="rounded-full bg-[#f47b63] px-2 py-0.5 text-[10px] font-bold text-[#421f1b]">{{ $pendingCount }}</span>
                @endif
            </a>
        @endif

        @if ($currentUser->isAdmin() || $currentUser->isCollector())
            <a href="{{ route('collections.index') }}" class="{{ request()->routeIs('collections.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fas fa-route w-5 text-center" aria-hidden="true"></i><span>{{ $currentUser->isAdmin() ? 'Collections' : 'My pickups' }}</span>
            </a>
        @endif

        @if ($currentUser->isAdmin())
            <a href="{{ route('trucks.index') }}" class="{{ request()->routeIs('trucks.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fas fa-truck w-5 text-center" aria-hidden="true"></i><span>Fleet</span>
            </a>
            <a href="{{ route('iot.simulate') }}" class="{{ request()->routeIs('iot.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                <i class="fas fa-microchip w-5 text-center" aria-hidden="true"></i><span>Sensor simulator</span>
            </a>
        @endif

        <a href="{{ route('map') }}" class="{{ request()->routeIs('map') ? 'sidebar-link-active' : 'sidebar-link' }}">
            <i class="fas fa-map-location-dot w-5 text-center" aria-hidden="true"></i><span>City map</span>
        </a>
    </nav>

    <div class="shrink-0 border-t border-white/10 p-4">
        <div class="mb-4 flex items-center gap-3 px-2">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-white/10 font-bold text-[#d6ef91]">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-semibold">{{ $currentUser->name }}</span>
                <span class="mt-0.5 block text-xs capitalize text-white/50">{{ $currentRole }}</span>
            </span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-sm font-medium text-white/70 transition hover:bg-[#6d3028] hover:text-white">
                <i class="fas fa-arrow-right-from-bracket w-5 text-center" aria-hidden="true"></i> Sign out
            </button>
        </form>
    </div>
</aside>

<div class="min-h-screen lg:pl-[270px]">
    <header class="sticky top-0 z-20 border-b border-[#e4e9e2] bg-white/95 px-4 py-4 backdrop-blur sm:px-7 lg:px-10">
        <div class="flex min-h-10 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-3">
                <button id="sidebarToggle" type="button" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50 lg:hidden" aria-label="Open navigation" aria-expanded="false">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>
                <div class="min-w-0">
                    <h1 class="truncate text-xl font-bold text-gray-950 sm:text-2xl">@yield('title', 'Dashboard')</h1>
                    <p class="hidden text-xs text-gray-500 sm:block">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
            <span class="inline-flex shrink-0 items-center gap-2 rounded-md border border-[#d4ecdc] bg-primary-50 px-3 py-2 text-xs font-bold capitalize text-primary-800">
                <span class="h-2 w-2 rounded-full bg-primary-500"></span>{{ ucfirst($currentRole) }}
            </span>
        </div>
    </header>

    <main class="mx-auto w-full max-w-[1500px] px-4 py-6 sm:px-7 sm:py-8 lg:px-10">
        @if (session('success'))
            <div class="alert-success" role="status">
                <span><i class="fas fa-circle-check mr-2" aria-hidden="true"></i>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="ml-3" aria-label="Dismiss notification"><i class="fas fa-xmark" aria-hidden="true"></i></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-error" role="alert">
                <span><i class="fas fa-circle-exclamation mr-2" aria-hidden="true"></i>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="ml-3" aria-label="Dismiss notification"><i class="fas fa-xmark" aria-hidden="true"></i></button>
            </div>
        @endif
        <div class="page-enter">@yield('content')</div>
    </main>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const sidebar = document.getElementById('appSidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggle = document.getElementById('sidebarToggle');

    function setSidebarOpen(isOpen) {
        sidebar.classList.toggle('-translate-x-full', !isOpen);
        sidebarBackdrop.classList.toggle('hidden', !isOpen);
        sidebarToggle.setAttribute('aria-expanded', String(isOpen));
    }

    sidebarToggle.addEventListener('click', () => setSidebarOpen(sidebar.classList.contains('-translate-x-full')));
    sidebarBackdrop.addEventListener('click', () => setSidebarOpen(false));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setSidebarOpen(false);
    });
</script>
@stack('scripts')
</body>
</html>
