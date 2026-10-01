<x-guest-layout>
    <div class="mb-8">
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-primary-700">Welcome back</p>
        <h2 class="text-3xl font-bold tracking-tight text-gray-950">Sign in</h2>
        <p class="mt-2 text-sm text-gray-500">Use your account to continue.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="input-label">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus class="input-field @error('email') input-error @enderror">
            @error('email')<p class="error-text">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="mb-1 flex items-center justify-between gap-3">
                <label for="password" class="input-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-primary-700 hover:text-primary-900">Forgot password?</a>
                @endif
            </div>
            <input id="password" name="password" type="password" autocomplete="current-password" required class="input-field @error('password') input-error @enderror">
            @error('password')<p class="error-text">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-gray-600">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" name="remember">
                <span>Remember me</span>
            </label>
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Sign in <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
        </button>
    </form>
</x-guest-layout>
