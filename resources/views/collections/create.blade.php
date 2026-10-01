@extends('layouts.dashboard')

@section('title', 'Assign Collection')

@section('content')

<div class="max-w-2xl">
	<div class="card">
		<form action="{{ route('collections.store') }}" method="POST">
			@csrf

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div class="md:col-span-2">
					<label for="bin_id" class="block text-sm font-medium text-gray-700 mb-1">Full Bin *</label>
					<select id="bin_id" name="bin_id" class="input-field @error('bin_id') border-red-500 @enderror" required>
						<option value="">Select a full bin</option>
						@foreach ($fullBins as $bin)
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
					<label for="truck_id" class="block text-sm font-medium text-gray-700 mb-1">Available Truck *</label>
					<select id="truck_id" name="truck_id" class="input-field @error('truck_id') border-red-500 @enderror" required>
						<option value="">Select a truck</option>
						@foreach ($trucks as $truck)
							<option value="{{ $truck->id }}" @selected(old('truck_id') == $truck->id)>
								{{ $truck->plate_number }} - {{ $truck->driver_name }}
							</option>
						@endforeach
					</select>
					@error('truck_id')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				<div>
					<label for="collector_id" class="block text-sm font-medium text-gray-700 mb-1">Collector</label>
					<select id="collector_id" name="collector_id" class="input-field @error('collector_id') border-red-500 @enderror">
						<option value="">Unassigned</option>
						@foreach ($collectors as $collector)
							<option value="{{ $collector->id }}" @selected(old('collector_id') == $collector->id)>
								{{ $collector->name }}
							</option>
						@endforeach
					</select>
					@error('collector_id')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

			</div>

			<div class="flex justify-end gap-3 mt-6 pt-4 border-t">
				<a href="{{ route('collections.index') }}" class="btn-secondary">Cancel</a>
				<button type="submit" class="btn-primary">
					<i class="fas fa-save mr-2"></i> Assign Collection
				</button>
			</div>
		</form>
	</div>
</div>

@endsection
