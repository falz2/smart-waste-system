@extends('layouts.dashboard')

@section('title', 'Report Details')

@section('content')

<div class="max-w-3xl">
    <div class="card">

        <div class="flex justify-between items-start mb-6 pb-4 border-b">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    {{ ucfirst(str_replace('_', ' ', $report->type)) }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Reported {{ $report->created_at->format('F j, Y \a\t g:i A') }}
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

        <div class="space-y-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Reported By</p>
                <p class="text-gray-800">{{ $report->user->name ?? 'Unknown' }}</p>
                <p class="text-sm text-gray-500">{{ $report->user->phone ?? '' }}</p>
            </div>

            @if($report->bin)
                <div>
                    <p class="text-sm font-medium text-gray-500">Related Bin</p>
                    <p class="text-gray-800">{{ $report->bin->bin_code }} - {{ $report->bin->location_name }}</p>
                </div>
            @endif

            @if($report->description)
                <div>
                    <p class="text-sm font-medium text-gray-500">Description</p>
                    <p class="text-gray-800">{{ $report->description }}</p>
                </div>
            @endif

            @if($report->latitude)
                <div>
                    <p class="text-sm font-medium text-gray-500">GPS Location</p>
                    <p class="text-gray-800">{{ $report->latitude }}, {{ $report->longitude }}</p>
                </div>
            @endif
        </div>

        <div class="mt-6 pt-6 border-t">
            <form action="{{ route('reports.updateStatus', $report) }}" method="POST" class="flex gap-3">
                @csrf
                @method('PATCH')

                <select name="status" class="input-field flex-1">
                    @foreach(['pending', 'in_progress', 'resolved'] as $status)
                        <option value="{{ $status }}" {{ $report->status === $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-check mr-2"></i> Update Status
                </button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>
    </div>
</div>

@endsection