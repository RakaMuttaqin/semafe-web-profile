<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SEMAFE - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-gray-100 font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-full">

        <!-- Sidebar -->
        @include('components.layouts.partials.sidebar')

        <!-- Overlay untuk mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" style="display: none;"></div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Topbar -->
            @include('components.layouts.partials.topbar')

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')

    {{-- Flash message --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => (show = false), 4000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed bottom-6 right-6 z-[70] flex items-center gap-3 px-4 py-3 bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-1.5 rounded-lg bg-green-100">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-800">{{ session('success') }}</p>
            <button @click="show = false" class="ml-2 text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
</body>

</html>
