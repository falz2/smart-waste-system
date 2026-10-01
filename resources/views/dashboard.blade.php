@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h2 class="text-2xl font-bold text-gray-950 sm:text-3xl">{{ $role === 'admin' ? 'Kampala operations' : ($role === 'collector' ? 'Assigned pickups' : 'My reports') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ Auth::user()->name }} <span class="px-1">·</span> {{ now()->format('M j, Y') }}</p>
    </div>
    @if ($role === 'resident')
        <a href="{{ route('reports.create') }}" class="btn-primary shrink-0"><i class="fas fa-plus mr-2" aria-hidden="true"></i>Submit a report</a>
    @elseif ($role === 'admin')
        <a href="{{ route('collections.create') }}" class="btn-primary shrink-0"><i class="fas fa-route mr-2" aria-hidden="true"></i>Assign a pickup</a>
    @endif
</div>

@if ($role === 'admin')
    <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="stat-card border-l-4 border-l-primary-500">
            <div class="flex items-center justify-between text-sm text-gray-500"><span>Active bins</span><i class="fas fa-dumpster text-primary-700" aria-hidden="true"></i></div>
            <p class="mt-3 text-3xl font-bold text-gray-950">{{ $stats['total_bins'] }}</p>
        </div>
        <div class="stat-card border-l-4 border-l-[#e36b55]">
            <div class="flex items-center justify-between text-sm text-gray-500"><span>Need collection</span><i class="fas fa-triangle-exclamation text-[#c94e3b]" aria-hidden="true"></i></div>
            <p class="mt-3 text-3xl font-bold text-gray-950">{{ $stats['full_bins'] }}</p>
        </div>
        <div class="stat-card border-l-4 border-l-[#e4ad37]">
            <div class="flex items-center justify-between text-sm text-gray-500"><span>Pending reports</span><i class="fas fa-flag text-[#b98215]" aria-hidden="true"></i></div>
            <p class="mt-3 text-3xl font-bold text-gray-950">{{ $stats['pending_reports'] }}</p>
        </div>
        <div class="stat-card border-l-4 border-l-[#547d9a]">
            <div class="flex items-center justify-between text-sm text-gray-500"><span>Available trucks</span><i class="fas fa-truck text-[#547d9a]" aria-hidden="true"></i></div>
            <p class="mt-3 text-3xl font-bold text-gray-950">{{ $stats['available_trucks'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
        <section class="card">
            <div class="mb-4 flex items-center justify-between gap-3 border-b border-gray-100 pb-4">
                <h3 class="font-bold text-gray-950">Bins needing collection</h3>
                <a href="{{ route('bins.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-900">All bins</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($fullBins as $bin)
                    <a href="{{ route('bins.show', $bin) }}" class="flex items-center justify-between gap-4 py-3 hover:bg-gray-50">
                        <span class="min-w-0"><span class="block font-semibold text-gray-800">{{ $bin->bin_code }}</span><span class="mt-0.5 block truncate text-xs text-gray-500">{{ $bin->location_name }}</span></span>
                        <span class="badge-danger shrink-0">{{ $bin->fill_level }}%</span>
                    </a>
                @empty
                    <p class="py-8 text-center text-sm text-gray-400">No full bins right now.</p>
                @endforelse
            </div>
        </section>

        <section class="card">
            <div class="mb-4 flex items-center justify-between gap-3 border-b border-gray-100 pb-4">
                <h3 class="font-bold text-gray-950">Recent reports</h3>
                <a href="{{ route('reports.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-900">All reports</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($recentReports as $report)
                    <a href="{{ route('reports.show', $report) }}" class="flex items-center justify-between gap-4 py-3 hover:bg-gray-50">
                        <span class="min-w-0"><span class="block font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $report->type)) }}</span><span class="mt-0.5 block truncate text-xs text-gray-500">{{ $report->bin->location_name ?? 'GPS report' }} · {{ $report->created_at->diffForHumans() }}</span></span>
                        <span class="badge {{ $report->status === 'pending' ? 'badge-warning' : ($report->status === 'resolved' ? 'badge-success' : 'badge-info') }}">{{ str_replace('_', ' ', $report->status) }}</span>
                    </a>
                @empty
                    <p class="py-8 text-center text-sm text-gray-400">No reports have been submitted.</p>
                @endforelse
            </div>
        </section>
    </div>
@elseif ($role === 'collector')
    <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="stat-card border-l-4 border-l-[#e4ad37]"><p class="text-sm text-gray-500">Active pickups</p><p class="mt-2 text-3xl font-bold text-gray-950">{{ $stats['active_collections'] }}</p></div>
        <div class="stat-card border-l-4 border-l-primary-500"><p class="text-sm text-gray-500">Completed pickups</p><p class="mt-2 text-3xl font-bold text-gray-950">{{ $stats['completed_collections'] }}</p></div>
    </div>
    <section class="card">
        <div class="mb-4 border-b border-gray-100 pb-4"><h3 class="font-bold text-gray-950">My assigned pickups</h3></div>
        <div class="divide-y divide-gray-100">
            @forelse ($collections as $collection)
                <div class="flex flex-wrap items-center justify-between gap-4 py-4">
                    <div class="min-w-0"><p class="font-semibold text-gray-900">{{ $collection->bin->bin_code ?? 'Bin unavailable' }} <span class="font-normal text-gray-400">/</span> {{ $collection->bin->location_name ?? '' }}</p><p class="mt-1 text-sm text-gray-500">{{ $collection->truck->plate_number ?? 'No truck' }} <span class="px-1">·</span> Assigned {{ $collection->created_at->format('M j') }}</p></div>
                    <div class="flex items-center gap-2">
                        <span class="badge {{ $collection->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ str_replace('_', ' ', $collection->status) }}</span>
                        <a href="{{ route('collections.show', $collection) }}" class="btn-secondary px-3 py-2 text-sm">Open</a>
                        @if ($collection->status !== 'completed')
                            <form action="{{ route('collections.complete', $collection) }}" method="POST">@csrf @method('PATCH')<button type="submit" class="btn-success px-3 py-2 text-sm">Complete</button></form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-12 text-center"><i class="fas fa-circle-check mb-3 text-2xl text-primary-500" aria-hidden="true"></i><p class="font-semibold text-gray-700">No active pickups assigned</p><p class="mt-1 text-sm text-gray-500">New assignments will appear here.</p></div>
            @endforelse
        </div>
        <a href="{{ route('collections.index') }}" class="mt-4 inline-flex text-sm font-semibold text-primary-700 hover:text-primary-900">View pickup history <i class="fas fa-arrow-right ml-2 mt-0.5" aria-hidden="true"></i></a>
    </section>
@else
    <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="stat-card border-l-4 border-l-primary-500"><p class="text-sm text-gray-500">Reports submitted</p><p class="mt-2 text-3xl font-bold text-gray-950">{{ $stats['my_reports'] }}</p></div>
        <div class="stat-card border-l-4 border-l-[#e4ad37]"><p class="text-sm text-gray-500">Awaiting review</p><p class="mt-2 text-3xl font-bold text-gray-950">{{ $stats['pending_reports'] }}</p></div>
        <div class="stat-card border-l-4 border-l-[#547d9a]"><p class="text-sm text-gray-500">Resolved</p><p class="mt-2 text-3xl font-bold text-gray-950">{{ $stats['resolved_reports'] }}</p></div>
    </div>
    <section class="card">
        <div class="mb-4 flex items-center justify-between gap-3 border-b border-gray-100 pb-4"><h3 class="font-bold text-gray-950">My recent reports</h3><a href="{{ route('reports.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-900">All reports</a></div>
        <div class="divide-y divide-gray-100">
            @forelse ($recentReports as $report)
                <a href="{{ route('reports.show', $report) }}" class="flex flex-wrap items-center justify-between gap-3 py-4 hover:bg-gray-50">
                    <span><span class="block font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $report->type)) }}</span><span class="mt-1 block text-xs text-gray-500">{{ $report->bin->location_name ?? 'Location supplied' }} · {{ $report->created_at->format('M j, Y') }}</span></span>
                    <span class="badge {{ $report->status === 'pending' ? 'badge-warning' : ($report->status === 'resolved' ? 'badge-success' : 'badge-info') }}">{{ str_replace('_', ' ', $report->status) }}</span>
                </a>
            @empty
                <div class="py-12 text-center"><p class="font-semibold text-gray-700">No reports yet</p><p class="mt-1 text-sm text-gray-500">Your submitted reports will be listed here.</p></div>
            @endforelse
        </div>
    </section>
@endif

@endsection
