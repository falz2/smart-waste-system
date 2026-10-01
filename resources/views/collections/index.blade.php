@extends('layouts.dashboard')

@section('title', 'Collections')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600">Track collection assignments</p>
    <a href="{{ route('collections.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i> New Collection
    </a>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                    <th class="py-3 px-4">Bin</th>
                    <th class="py-3 px-4">Truck</th>
                    <th class="py-3 px-4">Collector</th>
                    <th class="py-3 px-4">Assigned</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collections as $collection)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <p class="font-semibold text-gray-800">{{ $collection->bin->bin_code ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $collection->bin->location_name ?? '' }}</p>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $collection->truck->plate_number ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $collection->collector->name ?? 'Unassigned' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-500">
                            {{ $collection->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $badgeClass = match($collection->status) {
                                    'assigned' => 'bg-yellow-100 text-yellow-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', $collection->status) }}</span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($collection->status !== 'completed')
                                <form action="{{ route('collections.complete', $collection) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn-success text-sm">
                                        <i class="fas fa-check"></i> Complete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400">No collections yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $collections->links() }}</div>
</div>

@endsection