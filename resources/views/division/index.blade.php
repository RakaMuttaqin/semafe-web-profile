@extends('components.layouts.app')

@section('title', 'Divisi')

@section('content')
    <div x-data="divisionTable">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Divisi</h2>
            <p class="text-sm text-gray-500">Kelola divisi dan koordinator SEMAFE</p>
            <div class="flex flex-col sm:flex-row gap-4">
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
                            <th class="pb-3 font-medium text-left">Status</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" x-show="divisionFiltered.length > 0">
                        <template x-for="division in divisionFiltered" :key="division.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 font-medium text-gray-900" x-text="division.name"></td>
                                <td class="py-4 text-gray-500 font-mono" x-text="division.slug"></td>
                                <td class="py-4">
                                    <div class="flex items-center gap-2">
                                        <img :src="division.photos" :alt="division.coordinator"
                                            class="w-8 h-8 rounded-full object-cover">
                                        <span class="text-sm text-gray-700" x-text="division.coordinator"></span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :style="'background-color: ' + division.color + '20; color: ' + division.color"
                                        x-text="division.member_count + ' anggota'"></span>
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium"
                                        :class="{
                                            'text-green-600': user.status === 'active',
                                            'text-gray-500': user.status === 'inactive',
                                        }">
                                        <span class="w-2 h-2 rounded-full"
                                            :class="{
                                                'bg-green-500': user.status === 'active',
                                                'bg-gray-400': user.status === 'inactive',
                                            }">
                                        </span>

                                        <span x-text="{active: 'Aktif',inactive: 'Nonaktif'}[division.status]">
                                        </span>
                                    </span>
                                </td>
                                <td class="py-4 text-right space-x-2">
                                    <button @click="selected = div; openEdit = true"
                                        class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Edit</button>
                                    <button @click="selected = div; openDelete = true"
                                        class="px-3 py-1.5 text-xs text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Hapus</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody x-show="divisionFiltered.length === 0">
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada data divisi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openInsert" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none"
            x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openInsert = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Tambah Divisi</h3>
                <form :action="'/division/store/'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id" :value="selected.id">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Divisi</label>
                        <input type="text" name="name" :value="selected.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    {{-- <div>
                        <label class="block text-sm text-gray-600 mb-1">Warna Tema</label>
                        <input type="color" name="color" :value="selected.color"
                            class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                    </div> --}}
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none"
            x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openEdit = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Edit Divisi</h3>
                <form :action="'/division/update' + selected.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="selected.id">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Divisi</label>
                        <input type="text" name="name" :value="selected.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Warna Tema</label>
                        <input type="color" name="color" :value="selected.color"
                            class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg" @click.away="openDelete = false">
                <h3 class="mb-2 text-lg font-semibold text-gray-800">Hapus Divisi?</h3>
                <p class="mb-4 text-sm text-gray-500">Data <span x-text="selected.name"
                        class="font-medium text-red-600"></span> akan dihapus permanen.</p>
                <form :action="'/division/delete' + selected.id" method="POST" class="flex justify-end space-x-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDelete = false"
                        class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Hapus
                        Permanen</button>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- @php
    $divisions = [
        [
            'id' => 1,
            'name' => 'Humas',
            'slug' => 'humas',
            'color' => '#6366f1',
            'icon' =>
                'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            'coordinator' => 'Andi Pratama',
            'coordinator_photo' => 'https://ui-avatars.com/api/?name=Andi+Pratama&background=6366f1&color=fff&size=64',
            'member_count' => 28,
        ],
        [
            'id' => 2,
            'name' => 'Media',
            'slug' => 'media',
            'color' => '#a855f7',
            'icon' =>
                'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z',
            'coordinator' => 'Siti Rahma',
            'coordinator_photo' => 'https://ui-avatars.com/api/?name=Siti+Rahma&background=a855f7&color=fff&size=64',
            'member_count' => 22,
        ],
        [
            'id' => 3,
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
            'color' => '#f97316',
            'icon' =>
                'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
            'coordinator' => 'Budi Santoso',
            'coordinator_photo' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=f97316&color=fff&size=64',
            'member_count' => 35,
        ],
        [
            'id' => 4,
            'name' => 'Danus',
            'slug' => 'danus',
            'color' => '#22c55e',
            'icon' =>
                'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'coordinator' => 'Dewi Lestari',
            'coordinator_photo' => 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=22c55e&color=fff&size=64',
            'member_count' => 18,
        ],
        [
            'id' => 5,
            'name' => 'PSDM',
            'slug' => 'psdm',
            'color' => '#3b82f6',
            'icon' =>
                'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            'coordinator' => 'Rizki Maulana',
            'coordinator_photo' => 'https://ui-avatars.com/api/?name=Rizki+Maulana&background=3b82f6&color=fff&size=64',
            'member_count' => 24,
        ],
    ];
@endphp --}}

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('divisionTable', () => ({
                divisions: @json($divisions),
                openInsert: false,
                openEdit: false,
                openDelete: false,
                selected: {},
                search: '',
                get divisionFiltered() {
                    if (!this.search) return this.divisions;
                    const s = this.search.toLowerCase();
                    return this.divisions.filter(d =>
                        d.name.toLowerCase().includes(s) ||
                        d.slug.toLowerCase().includes(s) ||
                        d.coordinator.toLowerCase().includes(s)
                    );
                }
            }));
        });
    </script>
@endpush
