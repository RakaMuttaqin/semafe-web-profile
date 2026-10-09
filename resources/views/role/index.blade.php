@extends('components.layouts.app')

@section('title', 'Peran')

@section('content')
@php
    $users = $users ?? [
        (object)['id' => 1, 'name' => 'Admin SEMAFE'],
        (object)['id' => 2, 'name' => 'Ketua'],
    ];
    $roles = $roles ?? [
        (object)['id' => 1, 'name' => 'Admin', 'slug' => 'admin', 'description' => 'Akses penuh', 'user_count' => 2],
        (object)['id' => 2, 'name' => 'Koordinator', 'slug' => 'koordinator', 'description' => 'Kelola divisi', 'user_count' => 8],
    ];
@endphp
    <div x-data="roleTable">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Peran</h2>
            <p class="text-sm text-gray-500">Kelola peran dan hak akses pengguna SEMAFE</p>
            <div class="flex flex-col sm:flex-row gap-4">

                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="relative w-full sm:w-64">
                        <input type="text" x-model="search" placeholder="Cari peran..."
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

                        Tambah Peran
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-medium text-left">Nama</th>
                            <th class="pb-3 font-medium text-left">Slug</th>
                            <th class="pb-3 font-medium text-left">Jumlah Pengguna</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" x-show="roleFiltered.length > 0">
                        <template x-for="role in roleFiltered" :key="role.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 font-medium text-gray-900" x-text="role.name"></td>
                                <td class="py-4 text-gray-500 font-mono" x-text="role.slug"></td>
                                <td class="py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700"
                                        x-text="role.users.count + ' pengguna'">
                                    </span>
                                </td>
                                <td class="py-4 text-right space-x-2">
                                    <button @click="selected = role; openEdit = true"
                                        class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Edit</button>
                                    <button @click="selected = role; openDelete = true"
                                        class="px-3 py-1.5 text-xs text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Hapus</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody x-show="roleFiltered.length === 0">
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data peran</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openInsert" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openInsert = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Tambah Peran</h3>
                <form action="{{ route('role.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Peran</label>
                        <input type="text" name="name" :value="selected.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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

        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none"
            x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openEdit = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Edit Peran</h3>
                <form :action="'/role/update/' + selected.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="selected.id">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Peran</label>
                        <input type="text" name="name" :value="selected.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Slug</label>
                        <input type="text" name="slug" :value="selected.slug"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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
                <h3 class="mb-2 text-lg font-semibold text-gray-800">Hapus Peran?</h3>
                <p class="mb-4 text-sm text-gray-500">Data <span x-text="selected.name"
                        class="font-medium text-red-600"></span> akan dihapus permanen.</p>
                <form :action="'/role/remove/' + selected.id" method="POST" class="flex justify-end space-x-2">
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
$roles = [
    ['id' => 1, 'name' => 'Admin', 'slug' => 'admin', 'description' => 'Akses penuh ke semua fitur sistem', 'user_count' => 2],
    ['id' => 2, 'name' => 'Koordinator Divisi', 'slug' => 'koordinator', 'description' => 'Mengelola anggota dan kegiatan divisi', 'user_count' => 8],
    ['id' => 3, 'name' => 'Anggota', 'slug' => 'anggota', 'description' => 'Akses dasar untuk partisipasi kegiatan', 'user_count' => 117],
    ['id' => 4, 'name' => 'Operator', 'slug' => 'operator', 'description' => 'Bantuan administrasi dan input data', 'user_count' => 10],
    ['id' => 5, 'name' => 'Alumni', 'slug' => 'alumni', 'description' => 'Akses terbatas hanya untuk arsip', 'user_count' => 45],
];
@endphp --}}

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('roleTable', () => ({
                roles: @json($roles),
                users: @json($users),
                openInsert: false,
                openEdit: false,
                openDelete: false,
                selected: {},
                search: '',
                get roleFiltered() {
                    if (!this.search) return this.roles;
                    const s = this.search.toLowerCase();
                    return this.roles.filter(r =>
                        r.name.toLowerCase().includes(s) ||
                        r.slug.toLowerCase().includes(s)
                    );
                }
            }));
        });
    </script>
@endpush
