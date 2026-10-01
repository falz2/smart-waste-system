<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    public function index()
    {
        $trucks = Truck::with('collections')->latest()->paginate(10);
        return view('trucks.index', compact('trucks'));
    }

    public function create()
    {
        return view('trucks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|unique:trucks',
            'driver_name' => 'required',
            'driver_phone' => 'required',
            'capacity' => 'integer|min:1',
        ]);

        Truck::create($validated);

        return redirect()->route('trucks.index')->with('success', 'Truck added');
    }

    public function edit(Truck $truck)
    {
        return view('trucks.edit', compact('truck'));
    }

    public function update(Request $request, Truck $truck)
    {
        $validated = $request->validate([
            'plate_number' => 'required|unique:trucks,plate_number,' . $truck->id,
            'driver_name' => 'required',
            'driver_phone' => 'required',
            'capacity' => 'integer|min:1',
            'status' => 'required|in:available,on_route,maintenance',
        ]);

        $truck->update($validated);

        return redirect()->route('trucks.index')->with('success', 'Truck updated');
    }

    public function destroy(Truck $truck)
    {
        $truck->delete();
        return redirect()->route('trucks.index')->with('success', 'Truck deleted');
    }
}