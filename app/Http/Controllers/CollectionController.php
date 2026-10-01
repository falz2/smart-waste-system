<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Bin;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $query = Collection::with('bin', 'truck', 'collector')->latest();
        if (request()->user()->isCollector()) {
            $query->where('collector_id', request()->user()->id);
        }

        $collections = $query->paginate(10);
        return view('collections.index', compact('collections'));
    }

    public function create()
    {
        $fullBins = Bin::active()->full()->get();
        $trucks = Truck::where('status', 'available')->get();
        $collectors = User::where('role', 'collector')->get();
        return view('collections.create', compact('fullBins', 'trucks', 'collectors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bin_id' => 'required|exists:bins,id',
            'truck_id' => 'required|exists:trucks,id',
            'collector_id' => 'nullable|exists:users,id',
        ]);

        $validated['status'] = 'assigned';
        Collection::create($validated);

        Truck::find($request->truck_id)->update(['status' => 'on_route']);

        return redirect()->route('collections.index')->with('success', 'Collection assigned');
    }

    public function show(Collection $collection, Request $request)
    {
        $this->ensureCanAccess($collection, $request);
        $collection->load('bin', 'truck', 'collector');
        return view('collections.show', compact('collection'));
    }

    public function complete(Collection $collection, Request $request)
    {
        $this->ensureCanAccess($collection, $request);

        $collection->update([
            'status' => 'completed',
            'collected_at' => now(),
        ]);

        $collection->bin->update([
            'fill_level' => 0,
            'status' => 'empty',
        ]);

        $activeCollections = Collection::where('truck_id', $collection->truck_id)
            ->where('status', '!=', 'completed')
            ->count();

        if ($activeCollections === 0) {
            $collection->truck->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Collection marked as complete');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('collections.index')->with('success', 'Collection deleted');
    }

    private function ensureCanAccess(Collection $collection, Request $request): void
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || ($user->isCollector() && $collection->collector_id === $user->id), 403);
    }
}