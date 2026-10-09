@extends('components.layouts.app')

@section('title', 'Kegiatan')

@section('content')
    <div x-data="eventsTable" class="relative">

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
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Kegiatan</h2>
                <p class="text-sm text-gray-500">Kelola kegiatan dan event SEMAFE</p>
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

                <button @click="selected = {}; openInsert = true" type="button"

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

        {{-- Grid Card --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3" x-show="eventsFiltered.length > 0">
            <template x-for="e in eventsFiltered" :key="e.id">

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
                            <button @click="selected = e; openEdit = true"
                                class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">Edit</button>
                            <button @click="selected = e; openDelete = true"
                                class="px-3 py-1.5 text-xs text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">Hapus</button>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <div x-show="eventsFiltered.length === 0"
            class="p-12 bg-white rounded-xl shadow-sm border border-gray-100 text-center text-gray-500">
            Tidak ada data kegiatan
        </div>

        {{-- Modal Tambah --}}
        <div x-show="openInsert" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-lg p-6 bg-white rounded-xl shadow-lg max-h-[90vh] overflow-y-auto"
                @click.away="openInsert = false">

                <h3 class="mb-4 text-lg font-semibold text-gray-800">Tambah Kegiatan</h3>

                <form action="{{ url('/events/store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Judul Kegiatan</label>
                        <input type="text" name="title" :value="selected.title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                        <textarea name="description" :value="selected.description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">URL Gambar</label>
                        <input type="url" name="image" :value="selected.image"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Lokasi</label>
                        <input type="text" name="location" :value="selected.location"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Mulai</label>
                            <input type="datetime-local" name="start_at" :value="selected.start_at" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Selesai</label>
                            <input type="datetime-local" name="end_at" :value="selected.end_at" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openInsert = false"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-lg p-6 bg-white rounded-xl shadow-lg max-h-[90vh] overflow-y-auto"
                @click.away="openEdit = false">

                <h3 class="mb-4 text-lg font-semibold text-gray-800">Edit Kegiatan</h3>

                <form :action="'/events/update' + selected.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="selected.id">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Judul Kegiatan</label>
                        <input type="text" name="title" :value="selected.title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            x-text="selected.description"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">URL Gambar</label>
                        <input type="url" name="image" :value="selected.image"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Lokasi</label>
                        <input type="text" name="location" :value="selected.location"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Mulai</label>
                            <input type="datetime-local" name="start_at" :value="selected.start_at" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Selesai</label>
                            <input type="datetime-local" name="end_at" :value="selected.end_at" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="draft" :selected="selected.status === 'draft'">Draft</option>
                            <option value="published" :selected="selected.status === 'published'">Published</option>
                            <option value="completed" :selected="selected.status === 'completed'">Selesai</option>
                            <option value="cancelled" :selected="selected.status === 'cancelled'">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Hapus --}}
        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg" @click.away="openDelete = false">

                <h3 class="mb-2 text-lg font-semibold text-gray-800">Hapus Kegiatan?</h3>
                <p class="mb-4 text-sm text-gray-500">Data <span x-text="selected.title"
                        class="font-medium text-red-600"></span> akan dihapus permanen.</p>

                <form :action="'/events/delete' + selected.id" method="POST" class="flex justify-end space-x-2">
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
    $events = [
        ['id' => 1, 'title' => 'Rapat Koordinasi Bulanan', 'slug' => 'rapat-koordinasi-bulanan', 'description' => 'Evaluasi program kerja bulan November dan rencana kegiatan Desember ditutup dengan rencana kerja akhir semester.', 'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80', 'location' => 'Sekretariat SEMAFE', 'start_at' => '2026-12-15T19:00', 'end_at' => '2026-12-15T21:00', 'date_display' => '15 Des 2026, 19:00 WIB', 'status' => 'published'],
        ['id' => 2, 'title' => 'Workshop Desain Grafis', 'slug' => 'workshop-desain-grafis', 'description' => 'Pelatihan desain grafis untuk seluruh anggota media. Materi: Figma, Canva, dan tipografi.', 'image' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=800&q=80', 'location' => 'Lab Komputer FE', 'start_at' => '2026-12-20T14:00', 'end_at' => '2026-12-20T17:00', 'date_display' => '20 Des 2026, 14:00 WIB', 'status' => 'draft'],
        ['id' => 3, 'title' => 'Bakti Sosial Akhir Tahun', 'slug' => 'bakti-sosial-akhir-tahun', 'description' => 'Kunjungan sosial dan santunan anak yatim sebagai wujud kepedulian SEMAFE.', 'image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80', 'location' => 'Panti Asuhan Al-Ikhlas', 'start_at' => '2026-12-25T08:00', 'end_at' => '2026-12-25T13:00', 'date_display' => '25 Des 2026, 08:00 WIB', 'status' => 'published'],
        ['id' => 4, 'title' => 'Musyawarah Besar', 'slug' => 'musyawarah-besar', 'description' => 'Mubes tahunan menentukan arah gerak organisasi periode berikutnya.', 'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80', 'location' => 'Aula Fakultas Ekonomi', 'start_at' => '2026-11-10T09:00', 'end_at' => '2026-11-11T16:00', 'date_display' => '10 Nov 2026, 09:00 WIB', 'status' => 'completed'],
        ['id' => 5, 'title' => 'LDK Anggota Baru', 'slug' => 'ldk-anggota-baru', 'description' => 'Latihan Dasar Kepemimpinan untuk anggota baru periode Januari.', 'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80', 'location' => 'Bumi Perkemahan', 'start_at' => '2026-10-20T07:00', 'end_at' => '2026-10-22T16:00', 'date_display' => '20 Okt 2026, 07:00 WIB', 'status' => 'cancelled'],
    ];
@endphp

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('eventsTable', () => ({
        events: @json($events),
        openInsert: false,
        openEdit: false,
        openDelete: false,
        selected: {},
        search: '',
        statusFilter: '',
        toast: {
            show: false,
            message: ''
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

        notify(message) {
            this.toast = { show: true, message };
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },

        get eventsFiltered() {
            let list = this.events;

            if (this.statusFilter) {
                list = list.filter(e => e.status === this.statusFilter);
            }

            if (!this.search) return list;

            const s = this.search.toLowerCase();

            return list.filter(e =>
                e.title.toLowerCase().includes(s) ||
                e.slug.toLowerCase().includes(s) ||
                e.location.toLowerCase().includes(s) ||
                e.description.toLowerCase().includes(s)
            );
        }
    }));
});
</script>
@endpush
