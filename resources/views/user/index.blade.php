@extends('components.layouts.app')

@section('title', 'Pengguna')

@section('content')
@php
    $roles = $roles ?? [
        (object)['id' => 1, 'name' => 'Admin'],
        (object)['id' => 2, 'name' => 'Operator'],
    ];
    $users = $users ?? [
        (object)['id' => 1, 'name' => 'Admin SEMAFE', 'email' => 'admin@semafe.ac.id', 'role' => (object)['name' => 'Admin'], 'role_color' => 'bg-red-100 text-red-700', 'status' => 'active', 'photo' => 'https://ui-avatars.com/api/?name=Admin+SEMAFE&background=dc2626&color=fff&size=128'],
        (object)['id' => 2, 'name' => 'Ketua', 'email' => 'ketua@semafe.ac.id', 'role' => (object)['name' => 'Admin'], 'role_color' => 'bg-red-100 text-red-700', 'status' => 'active', 'photo' => 'https://ui-avatars.com/api/?name=Ketua&background=dc2626&color=fff&size=128'],
    ];
@endphp
    <div x-data="userTable">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="search" placeholder="Cari pengguna..."
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

                    Tambah Pengguna
                </button>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-medium text-left">Nama</th>
                            <th class="pb-3 font-medium text-left">Email</th>
                            <th class="pb-3 font-medium text-left">Peran</th>
                            <th class="pb-3 font-medium text-left">Status</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" x-show="userFiltered.length > 0">
                        <template x-for="user in userFiltered" :key="user.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 font-medium text-gray-900" x-text="user.name"></td>
                                <td class="py-4 text-gray-500" x-text="user.email"></td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="user.role_color" x-text="user.role.name"></span>
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium"
                                        :class="{
                                            'text-green-600': user.status === 'active',
                                            'text-gray-500': user.status === 'inactive',
                                            'text-red-600': user.status === 'blocked'
                                        }">
                                        <span class="w-2 h-2 rounded-full"
                                            :class="{
                                                'bg-green-500': user.status === 'active',
                                                'bg-gray-400': user.status === 'inactive',
                                                'bg-red-500': user.status === 'blocked'
                                            }">
                                        </span>

                                        <span
                                            x-text="{active: 'Aktif',inactive: 'Nonaktif',blocked: 'Diblokir'}[user.status]">
                                        </span>
                                    </span>
                                </td>
                                <td class="py-4 text-right space-x-2">
                                    <button @click="selected = user; openEdit = true"
                                        class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Edit</button>
                                    {{-- <button @click="selected = user; openDelete = true"
                                        class="px-3 py-1.5 text-xs text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Hapus</button> --}}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody x-show="userFiltered.length === 0">
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada data pengguna</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openInsert" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openInsert = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Tambah Pengguna</h3>
                <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
                    @csrf
                    {{-- @method('POST') --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama</label>
                        <input type="text" name="name" :value="selected.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" :value="selected.email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" :value="selected.password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Peran</label>
                        <select name="role_id" x-model="selected.role_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openInsert = false"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan
                            Data</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none"
            x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openEdit = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Edit Pengguna</h3>
                <form :action="'/user/update/' + selected.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="selected.id">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama</label>
                        <input type="text" name="name" :value="selected.name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" :value="selected.email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" :value="selected.password"
                            placeholder="Kosongkan bila tidak ingin mengubah password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Peran</label>
                        <select name="role_id" x-model="selected.role_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Status</label>

                        <select name="status" x-model="selected.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="blocked">Blocked</option>
                        </select>
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
                <h3 class="mb-2 text-lg font-semibold text-gray-800">Hapus Pengguna?</h3>
                <p class="mb-4 text-sm text-gray-500">Data <span x-text="selected.name"
                        class="font-medium text-red-600"></span> akan dihapus.</p>
                <form :action="'/user/delete/' + selected.id" method="POST" class="flex justify-end space-x-2">
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
    $users = [
        [
            'id' => 1,
            'name' => 'Admin SEMAFE',
            'email' => 'admin@semafe.ac.id',
            'role' => 'Admin',
            'role_color' => 'bg-red-100 text-red-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Admin+SEMAFE&background=dc2626&color=fff&size=128',
        ],
        [
            'id' => 2,
            'name' => 'Ketua Umum',
            'email' => 'ketua@semafe.ac.id',
            'role' => 'Admin',
            'role_color' => 'bg-red-100 text-red-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Ketua+Umum&background=dc2626&color=fff&size=128',
        ],
        [
            'id' => 3,
            'name' => 'Sekretaris',
            'email' => 'sekretaris@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Sekretaris&background=2563eb&color=fff&size=128',
        ],
        [
            'id' => 4,
            'name' => 'Bendahara',
            'email' => 'bendahara@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Bendahara&background=2563eb&color=fff&size=128',
        ],
        [
            'id' => 5,
            'name' => 'Koordinator Humas',
            'email' => 'humas@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Nonaktif',
            'photo' => 'https://ui-avatars.com/api/?name=Koordinator+Humas&background=2563eb&color=fff&size=128',
        ],
        [
            'id' => 6,
            'name' => 'Koordinator Media',
            'email' => 'media@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Koordinator+Media&background=2563eb&color=fff&size=128',
        ],
        [
            'id' => 7,
            'name' => 'Koordinator Kegiatan',
            'email' => 'kegiatan@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Koordinator+Kegiatan&background=2563eb&color=fff&size=128',
        ],
        [
            'id' => 8,
            'name' => 'Koordinator Danus',
            'email' => 'danus@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Koordinator+Danus&background=2563eb&color=fff&size=128',
        ],
        [
            'id' => 9,
            'name' => 'Koordinator PSDM',
            'email' => 'psdm@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Koordinator+PSDM&background=2563eb&color=fff&size=128',
        ],
        [
            'id' => 10,
            'name' => 'Staff Administrasi',
            'email' => 'staff@semafe.ac.id',
            'role' => 'Operator',
            'role_color' => 'bg-blue-100 text-blue-700',
            'status' => 'Aktif',
            'photo' => 'https://ui-avatars.com/api/?name=Staff+Administrasi&background=2563eb&color=fff&size=128',
        ],
    ];
@endphp --}}

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userTable', () => ({
                users: @json($users),
                openInsert: false,
                openEdit: false,
                openDelete: false,
                selected: {},
                search: '',
                editUser(user) {
                    this.selected = JSON.parse(JSON.stringify(user));
                    this.openEdit = true;
                },
                get userFiltered() {
                    if (!this.search) return this.users;
                    const s = this.search.toLowerCase();
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(s) ||
                        u.email.toLowerCase().includes(s) ||
                        u.role.name.toLowerCase().includes(s) ||
                        (u.status ?? '').toLowerCase().includes(s)
                    );
                }
            }));
        });
    </script>
@endpush
