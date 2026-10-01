<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use Illuminate\Http\Request;

class BinController extends Controller
{
    public function index()
    {
        $bins = Bin::latest()->paginate(10);
        return view('bins.index', compact('bins'));
    }

    public function create()
    {
        return view('bins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bin_code' => 'required|unique:bins',
            'location_name' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Bin::create($validated);

        return redirect()->route('bins.index')->with('success', 'Bin created successfully');
    }

    public function show(Bin $bin)
    {
        $bin->load('reports', 'collections');
        return view('bins.show', compact('bin'));
    }

    public function edit(Bin $bin)
    {
        return view('bins.edit', compact('bin'));
    }

    public function update(Request $request, Bin $bin)
    {
        $validated = $request->validate([
            'bin_code' => 'required|unique:bins,bin_code,' . $bin->id,
            'location_name' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'fill_level' => 'required|integer|min:0|max:100',
            'status' => 'required|in:empty,partial,full,collected',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $bin->update($validated);

        return redirect()->route('bins.index')->with('success', 'Bin updated successfully');
    }

    public function destroy(Bin $bin)
    {
        $bin->delete();
        return redirect()->route('bins.index')->with('success', 'Bin deleted successfully');
    }
}