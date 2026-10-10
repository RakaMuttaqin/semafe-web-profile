@extends('components.layouts.app')

@section('title', 'Divisi')

@section('content')
    <div x-data="divisionTable">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Divisi</h2>
                <p class="text-sm text-gray-500">Kelola divisi SEMAFE — divisi yang memiliki anggota tidak dapat dihapus</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="search" placeholder="Cari divisi..."
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <button @click="selected = {}; openInsert = true" type="button"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl hover:text-white hover:bg-indigo-600 hover:border-indigo-600 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200 cursor-pointer">
                    <span
                        class="flex items-center justify-center w-5 h-5 rounded-md bg-indigo-100 group-hover:bg-white/20 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>

                    Tambah Divisi
                </button>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-medium text-left">Nama Divisi</th>
                            <th class="pb-3 font-medium text-left">Slug</th>
                            <th class="pb-3 font-medium text-left">Koordinator</th>
                            <th class="pb-3 font-medium text-left">Jumlah Anggota</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50" x-show="divisionFiltered.length > 0">
                        <template x-for="division in divisionFiltered" :key="division.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 font-medium text-gray-900" x-text="division.name"></td>
                                <td class="py-4 text-gray-500 font-mono" x-text="division.slug"></td>
                                <td class="py-4">
                                    <template x-if="division.coordinator">
                                        <div class="flex items-center gap-2">
                                            <img :src="division.coordinator_photo" :alt="division.coordinator"
                                                class="w-8 h-8 rounded-full object-cover">
                                            <span class="text-sm text-gray-700" x-text="division.coordinator"></span>
                                        </div>
                                    </template>
                                    <span x-show="!division.coordinator" class="text-sm text-gray-400">— belum ada —</span>
                                </td>
                                <td class="py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700"
                                        x-text="division.members_count + ' anggota'"></span>
                                </td>
                                <td class="py-4 text-right">
                                    <button @click="selected = division; openEdit = true"
                                        class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">Edit</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>

                    <tbody x-show="divisionFiltered.length === 0">
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data divisi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah --}}
        <div x-show="openInsert" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openInsert = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Tambah Divisi</h3>

                <form action="{{ route('division.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Divisi</label>
                        <input type="text" name="name" :value="selected.name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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
        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none"
            x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openEdit = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Edit Divisi</h3>

                <form :action="'/division/update/' + selected.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="selected.id">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Divisi</label>
                        <input type="text" name="name" :value="selected.name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <p class="rounded-lg bg-yellow-50 px-3 py-2 text-xs text-yellow-700">
                        Divisi tidak dapat dihapus karena terhubung dengan data anggota. Kosongkan anggotanya terlebih
                        dahulu bila dividi ini ingin dinonaktifkan.
                    </p>

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
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('divisionTable', () => ({
                divisions: @json($divisions),
                openInsert: false,
                openEdit: false,
                selected: {},
                search: '',

                get divisionFiltered() {
                    if (!this.search) return this.divisions;
                    const s = this.search.toLowerCase();
                    return this.divisions.filter(d =>
                        d.name.toLowerCase().includes(s) ||
                        d.slug.toLowerCase().includes(s)
                    );
                }
            }));
        });
    </script>
@endpush
