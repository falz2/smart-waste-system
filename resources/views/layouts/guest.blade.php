<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Waste')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans text-gray-900 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[minmax(340px,0.9fr)_1.1fr]">
        <aside class="auth-grid relative flex min-h-[190px] flex-col justify-between overflow-hidden bg-[#123a2d] px-6 py-7 text-white sm:px-10 lg:min-h-screen lg:px-14 lg:py-12">
            <a href="{{ route('login') }}" class="relative z-10 inline-flex w-fit items-center gap-3" aria-label="Smart Waste sign in">
                <span class="flex h-11 w-11 items-center justify-center rounded-md bg-[#d6ef91] text-[#173d2d]">
                    <i class="fas fa-recycle text-xl" aria-hidden="true"></i>
                </span>
                <span class="text-lg font-bold">Smart Waste</span>
            </a>

            <div class="relative z-10 hidden max-w-md lg:block">
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-[#d6ef91]">Kampala City</p>
                <h1 class="text-4xl font-semibold leading-tight">Clean streets start with clear signals.</h1>
                <div class="mt-9 flex items-center gap-5 border-t border-white/20 pt-5 text-sm text-white/75">
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-[#d6ef91]"></span> Empty</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-[#f5be55]"></span> Partial</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-[#f47b63]"></span> Full</span>
                </div>
            </div>

            <div class="absolute -right-12 -top-16 hidden h-72 w-72 rotate-12 border border-white/10 lg:block" aria-hidden="true"></div>
            <div class="absolute -right-5 -top-9 hidden h-72 w-72 rotate-12 border border-white/10 lg:block" aria-hidden="true"></div>
            <p class="relative z-10 mt-8 text-xs text-white/50">SMART WASTE SYSTEM <span class="px-2">/</span> UGANDA</p>
        </aside>

        <main class="flex min-h-[calc(100vh-190px)] items-center justify-center px-5 py-8 sm:px-10 lg:min-h-screen lg:px-16">
            <div class="w-full max-w-md page-enter">
                <div class="mb-8 flex items-center justify-between gap-4">
                    <span class="text-xs font-bold uppercase tracking-[0.16em] text-gray-500">Secure access</span>
                    <a href="{{ request()->routeIs('register') ? route('login') : route('register') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-900">
                        {{ request()->routeIs('register') ? 'Sign in' : 'Create account' }}
                    </a>
                </div>
                {{ $slot }}
                <p class="mt-8 text-center text-xs text-gray-400">Kampala, Uganda <span class="px-1">·</span> {{ now()->format('Y') }}</p>
            </div>
        </main>
    </div>
</body>
</html>
