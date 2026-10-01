@extends('layouts.dashboard')

@section('title', 'Bin Details')

@section('content')

<div class="max-w-4xl space-y-6">
    <div class="card">
        <div class="flex justify-between items-start mb-6 pb-4 border-b">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $bin->bin_code }}</h2>
                <p class="text-gray-500">{{ $bin->location_name }}</p>
            </div>
            @php
                $statusBadge = match($bin->status) {
                    'empty' => 'bg-green-100 text-green-700',
                    'partial' => 'bg-yellow-100 text-yellow-700',
                    'full' => 'bg-red-100 text-red-700',
                    'collected' => 'bg-blue-100 text-blue-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <span class="badge {{ $statusBadge }}">{{ $bin->status }}</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500">Fill Level</p>
                <p class="text-lg font-bold">{{ $bin->fill_level }}%</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active</p>
                <p class="text-lg font-bold">{{ $bin->is_active ? 'Yes' : 'No' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Latitude</p>
                <p class="text-lg font-bold">{{ $bin->latitude }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Longitude</p>
                <p class="text-lg font-bold">{{ $bin->longitude }}</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
            <a href="{{ route('bins.index') }}" class="btn-secondary">Back</a>
            <a href="{{ route('bins.edit', $bin) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i> Edit Bin
            </a>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-flag text-blue-500"></i>
            Reports for this Bin
        </h3>
        @forelse ($bin->reports as $report)
            <div class="border-b py-3 flex justify-between items-center gap-4">
                <div>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $report->type)) }}</p>
                    <p class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('reports.show', $report) }}" class="badge bg-gray-100 text-gray-700 hover:bg-gray-200">
                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                </a>
            </div>
        @empty
            <p class="text-gray-400 text-center py-4">No reports for this bin</p>
        @endforelse
    </div>

    <div class="card">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-green-500"></i>
            Collection History
        </h3>
        @forelse ($bin->collections as $collection)
            <div class="border-b py-3 flex justify-between items-center gap-4">
                <div>
                    <p class="font-semibold">{{ $collection->truck->plate_number ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $collection->collected_at?->format('M d, Y g:i A') ?? 'Not yet collected' }}
                    </p>
                </div>
                <span class="badge bg-gray-100 text-gray-700">{{ ucfirst(str_replace('_', ' ', $collection->status)) }}</span>
            </div>
        @empty
            <p class="text-gray-400 text-center py-4">No collections yet</p>
        @endforelse
    </div>
</div>

@endsection
