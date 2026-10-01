<x-guest-layout>
    <div class="mb-7">
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-primary-700">Resident account</p>
        <h2 class="text-3xl font-bold tracking-tight text-gray-950">Create account</h2>
        <p class="mt-2 text-sm text-gray-500">Add your contact details to get started.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="input-label">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus class="input-field @error('name') input-error @enderror">
            @error('name')<p class="error-text">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="email" class="input-label">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required class="input-field @error('email') input-error @enderror">
                @error('email')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="input-label">Phone <span class="font-normal text-gray-400">(optional)</span></label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="input-field @error('phone') input-error @enderror">
                @error('phone')<p class="error-text">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="address" class="input-label">Area or address <span class="font-normal text-gray-400">(optional)</span></label>
            <input id="address" name="address" type="text" value="{{ old('address') }}" autocomplete="street-address" placeholder="e.g. Wandegeya, Kampala" class="input-field @error('address') input-error @enderror">
            @error('address')<p class="error-text">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="password" class="input-label">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required class="input-field @error('password') input-error @enderror">
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="input-label">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="input-field">
                @error('password_confirmation')<p class="error-text">{{ $message }}</p>@enderror
            </div>
        </div>

        <button type="submit" class="btn-primary mt-2 w-full py-3">
            Create resident account <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
        </button>
    </form>
</x-guest-layout>
