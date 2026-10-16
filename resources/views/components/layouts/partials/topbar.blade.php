@php
    $topbarNews = \Illuminate\Support\Facades\Schema::hasTable('news')
        ? \App\Models\News::where('status', 'published')
            ->latest('published_at')
            ->take(4)
            ->get()
            ->map(fn($n) => [
                'title' => $n->title,
                'slug' => $n->slug,
                'date' => optional($n->published_at)->translatedFormat('d M Y') ?? '-',
            ])
        : collect();
@endphp

<header x-data="{ notifOpen: false }"
    class="flex items-center justify-between h-16 px-4 bg-white border-b border-gray-200 lg:px-6">
    <!-- Left -->
    <div class="flex items-center space-x-4">
        <!-- Hamburger (mobile) -->
        <button @click="sidebarOpen = true" class="text-gray-500 lg:hidden hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
    </div>

    <!-- Right -->
    <div class="flex items-center space-x-4">
        <!-- Search (cari anggota) -->
        <form action="{{ url('/member') }}" method="GET" class="relative hidden md:block">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari anggota..."
                class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </form>

        <!-- Notifications -->
        <div class="relative">
            <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-500 rounded-lg hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if ($topbarNews->isNotEmpty())
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </button>

            <div x-show="notifOpen" @click.away="notifOpen = false" x-transition.opacity
                class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50"
                style="display:none">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">Pengumuman Terbaru</p>
                    <a href="{{ url('/admin/news') }}" class="text-xs text-indigo-600 hover:underline">Kelola</a>
                </div>

                <div class="max-h-80 overflow-y-auto">
                    @forelse ($topbarNews as $n)
                        <a href="{{ url('/news/' . $n['slug']) }}" target="_blank"
                            class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $n['title'] }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ $n['date'] }}</p>
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center text-sm text-gray-400">Belum ada pengumuman</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</header>
