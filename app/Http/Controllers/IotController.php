<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use Illuminate\Http\Request;

class IotController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'bin_code' => 'required|string|exists:bins,bin_code',
            'fill_level' => 'required|integer|min:0|max:100',
        ]);

        $bin = Bin::where('bin_code', $validated['bin_code'])->firstOrFail();
        $bin->fill_level = $validated['fill_level'];
        $bin->status = $this->statusFromFill($bin->fill_level);
        $bin->save();

        return response()->json([
            'success' => true,
            'bin_code' => $bin->bin_code,
            'fill_level' => $bin->fill_level,
            'status' => $bin->status,
            'received_at' => now()->toIso8601String(),
        ]);
    }

    public function simulate()
    {
        $bins = Bin::active()->orderBy('bin_code')->get();
        return view('iot.simulate', compact('bins'));
    }

    public function tick(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|integer|min:1|max:50',
        ]);
        $amount = $validated['amount'] ?? 10;
        $changes = [];

        foreach (Bin::active()->get() as $bin) {
            $increase = random_int(1, max(2, (int) ($amount * 1.5)));
            $bin->fill_level = min(100, $bin->fill_level + $increase);
            $bin->status = $this->statusFromFill($bin->fill_level);
            $bin->save();

            $changes[] = [
                'bin_code' => $bin->bin_code,
                'location' => $bin->location_name,
                'increase' => $increase,
                'fill_level' => $bin->fill_level,
                'status' => $bin->status,
            ];
        }

        return response()->json([
            'success' => true,
            'changes' => $changes,
        ]);
    }

    public function reset()
    {
        Bin::active()->update([
            'fill_level' => 0,
            'status' => 'empty',
        ]);

        return response()->json(['success' => true]);
    }

    private function statusFromFill(int $fill): string
    {
        if ($fill >= 80) {
            return 'full';
        }

        if ($fill >= 50) {
            return 'partial';
        }

        return 'empty';
    }
}
