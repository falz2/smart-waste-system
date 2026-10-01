@extends('layouts.dashboard')

@section('title', 'Edit Bin')

@section('content')

<div class="max-w-2xl">
    <div class="card">
        <form action="{{ route('bins.update', $bin) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bin Code *</label>
                    <input type="text" name="bin_code" value="{{ old('bin_code', $bin->bin_code) }}"
                           class="input-field" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Name *</label>
                    <input type="text" name="location_name" value="{{ old('location_name', $bin->location_name) }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude *</label>
                    <input type="text" name="latitude" value="{{ old('latitude', $bin->latitude) }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude *</label>
                    <input type="text" name="longitude" value="{{ old('longitude', $bin->longitude) }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fill Level (0-100%) *</label>
                    <input type="number" name="fill_level" min="0" max="100"
                           value="{{ old('fill_level', $bin->fill_level) }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="input-field" required>
                        @foreach(['empty', 'partial', 'full', 'collected'] as $status)
                            <option value="{{ $status }}" {{ old('status', $bin->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $bin->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('bins.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Update Bin
                </button>
            </div>
        </form>
    </div>
</div>

@endsection