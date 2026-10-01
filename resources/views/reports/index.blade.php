@extends('layouts.dashboard')

@section('title', Auth::user()->isAdmin() ? 'Reports' : 'My reports')

@section('content')

<div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
    <p class="text-sm text-gray-500">{{ $reports->total() }} reports</p>
    <a href="{{ route('reports.create') }}" class="btn-primary shrink-0">
        <i class="fas fa-plus mr-2" aria-hidden="true"></i> New report
    </a>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Type</th>
                    <th class="py-3 px-4">Reported By</th>
                    <th class="py-3 px-4">Location</th>
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-semibold text-gray-700">{{ $report->id }}</td>
                        <td class="py-3 px-4">
                            <span class="badge bg-blue-100 text-blue-700">
                                {{ ucfirst(str_replace('_', ' ', $report->type)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $report->user->name ?? 'Unknown' }}</td>
                        <td class="py-3 px-4 text-gray-600">
                            @if($report->bin)
                                {{ $report->bin->location_name }}
                            @else
                                <span class="text-gray-400">GPS: {{ $report->latitude }}, {{ $report->longitude }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-sm">
                            {{ $report->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $badgeClass = match($report->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'resolved' => 'bg-green-100 text-green-700',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $report->status }}</span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('reports.show', $report) }}"
                               class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition inline-block">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-400">No reports found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reports->links() }}</div>
</div>

@endsection