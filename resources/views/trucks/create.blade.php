@extends('layouts.dashboard')

@section('title', 'Add Truck')

@section('content')

<div class="max-w-2xl">
    <div class="card">
        <form action="{{ route('trucks.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number *</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number') }}"
                           placeholder="e.g., UBG 789C" class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name *</label>
                    <input type="text" name="driver_name" value="{{ old('driver_name') }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver Phone *</label>
                    <input type="text" name="driver_phone" value="{{ old('driver_phone') }}"
                           placeholder="e.g., 0780000003" class="input-field" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity (kg)</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 100) }}"
                           min="1" class="input-field">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('trucks.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Truck
                </button>
            </div>
        </form>
    </div>
</div>

@endsection