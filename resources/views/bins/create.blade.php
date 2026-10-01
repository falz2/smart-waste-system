@extends('layouts.dashboard')

@section('title', 'Add New Bin')

@section('content')

<div class="max-w-2xl">
    <div class="card">
        <form action="{{ route('bins.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bin Code *</label>
                    <input type="text" name="bin_code" value="{{ old('bin_code') }}"
                           placeholder="e.g., BIN-006"
                           class="input-field @error('bin_code') border-red-500 @enderror" required>
                    @error('bin_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Name *</label>
                    <input type="text" name="location_name" value="{{ old('location_name') }}"
                           placeholder="e.g., Kampala Road"
                           class="input-field @error('location_name') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude *</label>
                    <input type="text" name="latitude" value="{{ old('latitude', '0.3476') }}"
                           class="input-field" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude *</label>
                    <input type="text" name="longitude" value="{{ old('longitude', '32.5825') }}"
                           class="input-field" required>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('bins.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Bin
                </button>
            </div>
        </form>
    </div>
</div>

@endsection