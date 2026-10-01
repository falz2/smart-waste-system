<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Smart Waste System</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-trash-alt text-green-400"></i>
                Smart Waste
            </h1>
            <p class="text-xs text-gray-400 mt-1">Kampala City</p>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('bins.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('bins.*') ? 'bg-primary-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                <i class="fas fa-dumpster w-5"></i>
                <span>Bins</span>
            </a>

            <a href="{{ route('reports.index') }}"
               class="flex items-center justify-between px-4 py-3 rounded-lg transition {{ request()->routeIs('reports.*') ? 'bg-primary-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-flag w-5"></i>
                    <span>Reports</span>
                </span>
                @php $pendingCount = \App\Models\Report::where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('trucks.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('trucks.*') ? 'bg-primary-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                <i class="fas fa-truck w-5"></i>
                <span>Trucks</span>
            </a>

            <a href="{{ route('collections.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('collections.*') ? 'bg-primary-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                <i class="fas fa-clipboard-list w-5"></i>
                <span>Collections</span>
            </a>

            <a href="{{ route('map') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('map') ? 'bg-primary-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                <i class="fas fa-map-marked-alt w-5"></i>
                <span>Map View</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-sm bg-gray-700 hover:bg-red-600 py-2 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto">

        <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <h1 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
            <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
        </header>

        <div class="px-8 pt-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-4 flex items-center justify-between">
                    <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4 flex items-center justify-between">
                    <span><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        </div>

        <div class="p-8">
            @yield('content')
        </div>
    </main>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@stack('scripts')
</body>
</html>