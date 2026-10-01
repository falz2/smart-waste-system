<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('user', 'bin')->latest()->paginate(10);
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $bins = \App\Models\Bin::active()->get();
        return view('reports.create', compact('bins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:full_bin,missed_collection,illegal_dumping,damaged_bin',
            'bin_id' => 'nullable|exists:bins,id',
            'description' => 'nullable|string|max:1000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Report submitted successfully');
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Report status updated');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted');
    }
}