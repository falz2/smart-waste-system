@extends('layouts.dashboard')

@section('title', 'Fleet')

@section('content')

<div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
    <p class="text-sm text-gray-500">{{ $trucks->total() }} vehicles</p>
    <a href="{{ route('trucks.create') }}" class="btn-primary shrink-0">
        <i class="fas fa-plus mr-2" aria-hidden="true"></i> Add vehicle
    </a>
</div>

<div class="card !p-0">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px]">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                    <th class="px-4 py-3">Vehicle</th>
                    <th class="px-4 py-3">Driver</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Capacity</th>
                    <th class="px-4 py-3">Active pickups</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trucks as $truck)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $truck->plate_number }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $truck->driver_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $truck->driver_phone }}</td>
                        <td class="px-4 py-3 tabular-nums text-gray-700">{{ number_format($truck->capacity) }} kg</td>
                        <td class="px-4 py-3 tabular-nums text-gray-700">{{ $truck->collections->where('status', '!=', 'completed')->count() }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClass = match($truck->status) {
                                    'available' => 'badge-success',
                                    'on_route' => 'badge-info',
                                    'maintenance' => 'badge-warning',
                                    default => 'badge-gray',
                                };
                            @endphp
                            <span class="{{ $statusClass }}">{{ str_replace('_', ' ', $truck->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('trucks.edit', $truck) }}" class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900" title="Edit vehicle" aria-label="Edit {{ $truck->plate_number }}">
                                    <i class="fas fa-pen" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('trucks.destroy', $truck) }}" method="POST" onsubmit="return confirm('Delete this vehicle?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md p-2 text-red-600 hover:bg-red-50" title="Delete vehicle" aria-label="Delete {{ $truck->plate_number }}">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">No vehicles have been registered.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">{{ $trucks->links() }}</div>

@endsection