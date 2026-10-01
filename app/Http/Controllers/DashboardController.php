<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\Report;
use App\Models\Truck;
use App\Models\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bins' => Bin::active()->count(),
            'full_bins' => Bin::active()->full()->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'available_trucks' => Truck::where('status', 'available')->count(),
            'today_collections' => Collection::whereDate('created_at', today())->count(),
        ];

        $fullBins = Bin::active()->full()->latest()->take(5)->get();
        $recentReports = Report::with('user', 'bin')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'fullBins', 'recentReports'));
    }

    public function map()
    {
        $bins = Bin::active()->get();
        $reports = Report::where('status', '!=', 'resolved')->get();
        return view('map', compact('bins', 'reports'));
    }
}