@extends('layouts.dashboard')

@section('title', 'Waste Bins')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600">Manage all waste bins across Kampala</p>
    <a href="{{ route('bins.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i> Add New Bin
    </a>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                    <th class="py-3 px-4">Bin Code</th>
                    <th class="py-3 px-4">Location</th>
                    <th class="py-3 px-4">Fill Level</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Active</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bins as $bin)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-semibold text-gray-800">{{ $bin->bin_code }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $bin->location_name }}</td>
                        <td class="py-3 px-4">
                            <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                                @php
                                    $barColor = $bin->fill_level >= 80 ? 'bg-red-500' : ($bin->fill_level >= 50 ? 'bg-yellow-500' : 'bg-green-500');
                                @endphp
                                <div class="{{ $barColor }} h-6 flex items-center justify-center text-xs text-white font-semibold"
                                     style="width: {{ $bin->fill_level }}%">
                                    {{ $bin->fill_level }}%
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusBadge = match($bin->status) {
                                    'empty' => 'bg-green-100 text-green-700',
                                    'partial' => 'bg-yellow-100 text-yellow-700',
                                    'full' => 'bg-red-100 text-red-700',
                                    'collected' => 'bg-blue-100 text-blue-700',
                                };
                            @endphp
                            <span class="badge {{ $statusBadge }}">{{ $bin->status }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($bin->is_active)
                                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                            @else
                                <i class="fas fa-times-circle text-red-500 text-lg"></i>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('bins.show', $bin) }}"
                                   class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('bins.edit', $bin) }}"
                                   class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('bins.destroy', $bin) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this bin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400">No bins found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $bins->links() }}</div>
</div>

@endsection