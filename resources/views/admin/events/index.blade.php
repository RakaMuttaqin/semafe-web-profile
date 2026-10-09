@extends('components.layouts.app')

@section('title', 'Kelola Kegiatan')

@use(App\Models\Events)

@section('content')
    <div x-data="eventsCrud()" class="relative">

        {{-- Toast --}}
        <div x-show="toast.show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed top-20 right-6 z-[60] flex items-center gap-3 px-4 py-3 bg-white rounded-xl shadow-lg border border-gray-100"
            style="display:none">
            <div class="p-1.5 rounded-lg bg-green-100">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-800" x-text="toast.message"></p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Kelola Kegiatan</h2>
                <p class="text-sm text-gray-500">Buat dan kelola kegiatan SEMAFE — tampil di <a
                        href="{{ url('/events') }}" class="text-indigo-600 hover:underline">halaman publik</a></p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="search" placeholder="Cari kegiatan..."
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <select x-model="statusFilter"
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>

                <button @click="resetForm(); openInsert = true" type="button"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl hover:text-white hover:bg-indigo-600 hover:border-indigo-600 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200 cursor-pointer">
                    <span
                        class="flex items-center justify-center w-5 h-5 rounded-md bg-indigo-100 group-hover:bg-white/20 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                    Tambah Kegiatan
                </button>
            </div>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3" x-show="filtered.length > 0">
            <template x-for="e in filtered" :key="e.id">
                <article
                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="relative h-40 bg-gray-100">
                        <img :src="e.image" :alt="e.title" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                            :class="statusClass(e.status)" x-text="statusLabel(e.status)"></span>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 line-clamp-1" x-text="e.title"></h3>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-2" x-text="e.description"></p>

                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span x-text="e.date_display"></span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-text="e.location"></span>
                            </div>
                        </dl>

                        <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-gray-50">
                            <a :href="'/events/' + e.slug" target="_blank"
                                class="px-3 py-1.5 text-xs text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">Lihat</a>
                            <button @click="edit(e)"
                                class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">Edit</button>
                            <button @click="selected = e; openDelete = true"
                                class="px-3 py-1.5 text-xs text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">Hapus</button>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <div x-show="filtered.length === 0"
            class="p-12 bg-white rounded-xl shadow-sm border border-gray-100 text-center text-gray-500">
            Belum ada kegiatan
        </div>

        {{-- Modal Tambah/Edit --}}
        <div x-show="openInsert || openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-2xl p-6 bg-white rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto"
                @click.away="openInsert = false; openEdit = false">

                <h3 class="mb-5 text-lg font-semibold text-gray-800" x-text="openEdit ? 'Edit Kegiatan' : 'Tambah Kegiatan'"></h3>

                <form :action="action" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="openEdit"><input type="hidden" name="_method" value="PATCH"></template>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Judul Kegiatan</label>
                        <input type="text" name="title" x-model="form.title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Slug</label>
                            <input type="text" name="slug" x-model="form.slug" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Status</label>
                            <select name="status" x-model="form.status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                        <textarea name="description" x-model="form.description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">URL Gambar</label>
                            <input type="url" name="image" x-model="form.image" required placeholder="https://..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Lokasi</label>
                            <input type="text" name="location" x-model="form.location"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Waktu Mulai</label>
                            <input type="datetime-local" name="start_at" x-model="form.start_at"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Waktu Selesai</label>
                            <input type="datetime-local" name="end_at" x-model="form.end_at"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <template x-if="form.image">
                        <div>
                            <p class="block text-sm text-gray-600 mb-1">Pratinjau gambar</p>
                            <img :src="form.image" alt="Pratinjau"
                                class="h-32 w-full rounded-lg object-cover border border-gray-200">
                        </div>
                    </template>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openInsert = false; openEdit = false"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer"
                            x-text="openEdit ? 'Simpan Perubahan' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Hapus --}}
        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-sm p-6 bg-white rounded-2xl shadow-xl" @click.away="openDelete = false">

                <h3 class="mb-2 text-lg font-semibold text-gray-800">Hapus Kegiatan?</h3>
                <p class="mb-4 text-sm text-gray-500">Kegiatan <span x-text="selected.title"
                        class="font-medium text-red-600"></span> akan dihapus permanen.</p>

                <form :action="'/admin/events/delete/' + selected.id" method="POST" class="flex justify-end space-x-2">
                    @csrf
                    @method('DELETE')

                    <button type="button" @click="openDelete = false"
                        class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors cursor-pointer">Hapus
                        Permanen</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@php
    $fmt = fn($d, $f) => $d ? \Carbon\Carbon::parse($d)->translatedFormat($f) : null;

    $eventsList = Events::orderBy('start_at')->get()->map(function ($e) use ($fmt) {
        return [
            'id' => $e->id,
            'title' => $e->title,
            'slug' => $e->slug,
            'description' => \Illuminate\Support\Str::limit(strip_tags($e->description ?? ''), 120),
            'image' => $e->image ?: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=85',
            'location' => $e->location ?? '-',
            'start_at' => $fmt($e->start_at, 'Y-m-d\TH:i') ?? '',
            'end_at' => $fmt($e->end_at, 'Y-m-d\TH:i') ?? '',
            'date_display' => ($fmt($e->start_at, 'd M Y') ?? '-') . ' • ' . ($fmt($e->start_at, 'H:i') ?? '-') . ' WIB',
            'status' => $e->status,
        ];
    })->values();
@endphp

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('eventsCrud', () => ({
                events: @json($eventsList),
                openInsert: false,
                openEdit: false,
                openDelete: false,
                selected: {},
                form: {},
                search: '',
                statusFilter: '',
                toast: { show: false, message: '' },

                get action() {
                    return this.openEdit ? '/admin/events/update/' + this.selected.id :
                        '{{ route('admin.events.store') }}';
                },

                notify(message) {
                    this.toast = { show: true, message };
                    clearTimeout(this._toastTimer);
                    this._toastTimer = setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                },

                resetForm() {
                    this.selected = {};
                    this.form = {
                        title: '',
                        slug: '',
                        description: '',
                        image: '',
                        location: '',
                        start_at: '',
                        end_at: '',
                        status: 'published',
                    };
                    this.openEdit = false;
                },

                edit(e) {
                    this.selected = e;
                    this.form = { ...e };
                    this.openEdit = true;
                    this.openInsert = false;
                },

                statusClass(status) {
                    return {
                        'bg-green-100 text-green-700': status === 'published',
                        'bg-gray-100 text-gray-600': status === 'draft',
                        'bg-blue-100 text-blue-700': status === 'completed',
                        'bg-red-100 text-red-700': status === 'cancelled',
                    }[status] || 'bg-gray-100 text-gray-600';
                },

                statusLabel(status) {
                    return {
                        published: 'Published',
                        draft: 'Draft',
                        completed: 'Selesai',
                        cancelled: 'Dibatalkan',
                    }[status] || status;
                },

                get filtered() {
                    let list = this.events;

                    if (this.statusFilter) {
                        list = list.filter(e => e.status === this.statusFilter);
                    }

                    if (!this.search) return list;

                    const s = this.search.toLowerCase();

                    return list.filter(e =>
                        e.title.toLowerCase().includes(s) ||
                        e.slug.toLowerCase().includes(s) ||
                        e.location.toLowerCase().includes(s)
                    );
                }
            }));
        });
    </script>
@endpush