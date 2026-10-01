@extends('layouts.dashboard')

@section('title', 'Edit Truck')

@section('content')

<div class="max-w-2xl">
    <div class="card">
        <form action="{{ route('trucks.update', $truck) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number *</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number', $truck->plate_number) }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name *</label>
                    <input type="text" name="driver_name" value="{{ old('driver_name', $truck->driver_name) }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver Phone *</label>
                    <input type="text" name="driver_phone" value="{{ old('driver_phone', $truck->driver_phone) }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity (kg)</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $truck->capacity) }}"
                           min="1" class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="input-field" required>
                        @foreach(['available', 'on_route', 'maintenance'] as $status)
                            <option value="{{ $status }}" {{ old('status', $truck->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('trucks.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Update Truck
                </button>
            </div>
        </form>
    </div>
</div>

@endsection