@extends('layouts.dashboard')

@section('title', 'Bin Details')

@section('content')

<div class="max-w-3xl">
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
                };
            @endphp
            <span class="badge {{ $statusBadge }}">{{ $bin->status }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500">Fill Level</p>
                <p class="text-lg font-bold">{{ $bin->fill_level }}%</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-lg font-bold capitalize">{{ $bin->is_active ? 'Active' : 'Inactive' }}</p>
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

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('bins.index') }}" class="btn-secondary">Back</a>
            <a href="{{ route('bins.edit', $bin) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
        </div>
    </div>
</div>

@endsection