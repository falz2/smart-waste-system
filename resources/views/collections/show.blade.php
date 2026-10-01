@extends('layouts.dashboard')

@section('title', 'Collection Details')

@section('content')

<div class="max-w-3xl">
    <div class="card">
        <div class="flex justify-between items-start mb-6 pb-4 border-b">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Collection #{{ $collection->id }}</h2>
                <p class="text-gray-500">Assigned {{ $collection->created_at->format('F j, Y g:i A') }}</p>
            </div>
            @php
                $badgeClass = match($collection->status) {
                    'assigned' => 'bg-yellow-100 text-yellow-700',
                    'in_progress' => 'bg-blue-100 text-blue-700',
                    'completed' => 'bg-green-100 text-green-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $collection->status)) }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Bin</p>
                <p class="font-semibold text-gray-800">{{ $collection->bin->bin_code ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $collection->bin->location_name ?? '' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Truck</p>
                <p class="font-semibold text-gray-800">{{ $collection->truck->plate_number ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $collection->truck->driver_name ?? '' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Collector</p>
                <p class="font-semibold text-gray-800">{{ $collection->collector->name ?? 'Unassigned' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Collected At</p>
                <p class="font-semibold text-gray-800">
                    {{ $collection->collected_at?->format('F j, Y g:i A') ?? 'Not yet collected' }}
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
            <a href="{{ route('collections.index') }}" class="btn-secondary">Back</a>
            @if($collection->status !== 'completed')
                <form action="{{ route('collections.complete', $collection) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn-success">
                        <i class="fas fa-check mr-2"></i> Mark Complete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

@endsection
