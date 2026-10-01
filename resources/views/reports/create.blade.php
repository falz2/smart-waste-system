@extends('layouts.dashboard')

@section('title', 'New Report')

@section('content')

<div class="max-w-2xl">
    <div class="card">
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Report Type *</label>
                    <select id="type" name="type" class="input-field @error('type') border-red-500 @enderror" required>
                        <option value="">Select a report type</option>
                        <option value="full_bin" @selected(old('type') === 'full_bin')>Full Bin</option>
                        <option value="missed_collection" @selected(old('type') === 'missed_collection')>Missed Collection</option>
                        <option value="illegal_dumping" @selected(old('type') === 'illegal_dumping')>Illegal Dumping</option>
                        <option value="damaged_bin" @selected(old('type') === 'damaged_bin')>Damaged Bin</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bin_id" class="block text-sm font-medium text-gray-700 mb-1">Related Bin (optional)</label>
                    <select id="bin_id" name="bin_id" class="input-field @error('bin_id') border-red-500 @enderror">
                        <option value="">Not linked to a specific bin</option>
                        @foreach ($bins as $bin)
                            <option value="{{ $bin->id }}" @selected(old('bin_id') == $bin->id)>
                                {{ $bin->bin_code }} - {{ $bin->location_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('bin_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4" class="input-field @error('description') border-red-500 @enderror" placeholder="Describe the issue...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude', '0.3476') }}" class="input-field @error('latitude') border-red-500 @enderror">
                        @error('latitude')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude', '32.5825') }}" class="input-field @error('longitude') border-red-500 @enderror">
                        @error('longitude')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('reports.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
