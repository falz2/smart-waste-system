<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $query = Report::with('user', 'bin')->latest();
        if (! request()->user()->isAdmin()) {
            $query->where('user_id', request()->user()->id);
        }

        $reports = $query->paginate(10);
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

    public function show(Report $report, Request $request)
    {
        abort_unless($request->user()->isAdmin() || $report->user_id === $request->user()->id, 403);

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Report status updated');
    }

    public function destroy(Report $report, Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted');
    }
}