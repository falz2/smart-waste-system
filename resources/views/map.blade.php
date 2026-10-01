@extends('layouts.dashboard')

@section('title', 'Live Map')

@section('content')

<div class="card mb-6">
    <div class="flex flex-wrap gap-6 text-sm">
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span> Empty (0-49%)
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-yellow-500"></span> Partial (50-79%)
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500"></span> Full (80-100%)
        </span>
        <span class="flex items-center gap-2">
            <i class="fas fa-flag text-blue-500"></i> Citizen Report
        </span>
    </div>
</div>

<div class="card p-0 overflow-hidden">
    <div id="map" class="w-full" style="height: 600px;"></div>
</div>

@endsection

@push('scripts')
<script>
    var map = L.map('map').setView([0.3476, 32.5825], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    var bins = @json($bins);
    var reports = @json($reports);

    bins.forEach(function(bin) {
        var color = bin.fill_level >= 80 ? '#ef4444' : (bin.fill_level >= 50 ? '#f59e0b' : '#22c55e');

        L.circleMarker([bin.latitude, bin.longitude], {
            radius: 12,
            fillColor: color,
            color: '#fff',
            weight: 3,
            opacity: 1,
            fillOpacity: 0.85
        }).addTo(map).bindPopup(`
            <div style="font-family: sans-serif;">
                <strong style="font-size: 14px;">${bin.bin_code}</strong><br>
                <span style="color: #666;">${bin.location_name}</span><br>
                <hr style="margin: 6px 0;">
                Fill Level: <strong>${bin.fill_level}%</strong><br>
                Status: ${bin.status}
            </div>
        `);
    });

    reports.forEach(function(report) {
        if (report.latitude && report.longitude) {
            L.marker([report.latitude, report.longitude], {
                icon: L.divIcon({
                    className: '',
                    html: '<div style="background: #3b82f6; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"><i class="fas fa-flag"></i></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map).bindPopup(`
                <div style="font-family: sans-serif;">
                    <strong>Citizen Report</strong><br>
                    Type: ${report.type.replace('_', ' ')}<br>
                    Status: ${report.status}
                </div>
            `);
        }
    });
</script>
@endpush