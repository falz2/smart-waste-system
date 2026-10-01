@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm opacity-90">Total Bins</p>
                <p class="text-3xl font-bold mt-2">{{ $stats['total_bins'] }}</p>
            </div>
            <i class="fas fa-dumpster text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-red-500 to-red-600">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm opacity-90">Full Bins</p>
                <p class="text-3xl font-bold mt-2">{{ $stats['full_bins'] }}</p>
            </div>
            <i class="fas fa-exclamation-triangle text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-yellow-500 to-orange-500">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm opacity-90">Pending Reports</p>
                <p class="text-3xl font-bold mt-2">{{ $stats['pending_reports'] }}</p>
            </div>
            <i class="fas fa-flag text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm opacity-90">Available Trucks</p>
                <p class="text-3xl font-bold mt-2">{{ $stats['available_trucks'] }}</p>
            </div>
            <i class="fas fa-truck text-4xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="card">
        <div class="flex items-center justify-between mb-4 pb-4 border-b">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                Bins Needing Collection
            </h2>
            <a href="{{ route('collections.create') }}" class="text-sm text-primary-600 hover:underline">
                Assign Now
            </a>
        </div>

        <div class="space-y-3">
            @forelse($fullBins as $bin)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $bin->bin_code }}</p>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $bin->location_name }}
                        </p>
                    </div>
                    <span class="badge bg-red-100 text-red-700">{{ $bin->fill_level }}%</span>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-check-circle text-4xl text-green-400 mb-2"></i>
                    <p>No full bins at the moment</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-4 pb-4 border-b">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-flag text-blue-500"></i>
                Recent Citizen Reports
            </h2>
            <a href="{{ route('reports.index') }}" class="text-sm text-primary-600 hover:underline">
                View All
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recentReports as $report)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ ucfirst(str_replace('_', ' ', $report->type)) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            by {{ $report->user->name ?? 'Unknown' }} •
                            {{ $report->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @php
                        $badgeClass = match($report->status) {
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'in_progress' => 'bg-blue-100 text-blue-700',
                            'resolved' => 'bg-green-100 text-green-700',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $report->status }}</span>
                </div>
            @empty
                <p class="text-center py-8 text-gray-400">No recent reports</p>
            @endforelse
        </div>
    </div>
</div>

@endsection