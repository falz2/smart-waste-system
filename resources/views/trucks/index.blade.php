@extends('layouts.dashboard')

@section('title', 'Trucks')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600">Manage collection vehicles</p>
    <a href="{{ route('trucks.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i> Add Truck
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($trucks as $truck)
        <div class="card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $truck->plate_number }}</h3>
                    <p class="text-sm text-gray-500">{{ $truck->driver_name }}</p>
                </div>
                @php
                    $statusClass = match($truck->status) {
                        'available' => 'bg-green-100 text-green-700',
                        'on_route' => 'bg-blue-100 text-blue-700',
                        'maintenance' => 'bg-red-100 text-red-700',
                    };
                @endphp
                <span class="badge {{ $statusClass }}">{{ str_replace('_', ' ', $truck->status) }}</span>
            </div>

            <div class="space-y-2 text-sm text-gray-600 mb-4">
                <p><i class="fas fa-phone w-5"></i> {{ $truck->driver_phone }}</p>
                <p><i class="fas fa-weight-hanging w-5"></i> Capacity: {{ $truck->capacity }} kg</p>
                <p><i class="fas fa-clipboard-list w-5"></i> Collections: {{ $truck->collections->count() }}</p>
            </div>

            <div class="flex gap-2 pt-3 border-t">
                <a href="{{ route('trucks.edit', $truck) }}" class="btn-secondary flex-1 text-center text-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('trucks.destroy', $truck) }}" method="POST" class="inline"
                      onsubmit="return confirm('Delete this truck?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn-danger text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-12 text-gray-400">
            <i class="fas fa-truck text-5xl mb-3"></i>
            <p>No trucks registered</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $trucks->links() }}</div>

@endsection