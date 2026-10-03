@extends('components.layouts.app')

@section('title', 'Anggota')

@section('content')
    <div x-data="memberTable">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Anggota</h2>
            <p class="text-sm text-gray-500">Kelola data anggota SEMAFE</p>
            <div class="relative w-full sm:w-64">
                <input type="text" x-model="search" placeholder="Cari anggota (nama, NIM, divisi)..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-medium text-left">Foto</th>
                            <th class="pb-3 font-medium text-left">Nama</th>
                            <th class="pb-3 font-medium text-left">NIM</th>
                            <th class="pb-3 font-medium text-left">Divisi</th>
                            <th class="pb-3 font-medium text-left">Jabatan</th>
                            <th class="pb-3 font-medium text-left">Angkatan</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" x-show="memberFiltered.length > 0">
                        <template x-for="m in memberFiltered" :key="m.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">
                                    <img :src="m.photo" :alt="m.name" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                </td>
                                <td class="py-4 font-medium text-gray-900" x-text="m.name"></td>
                                <td class="py-4 text-gray-500 font-mono" x-text="m.nim"></td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :style="'background-color: ' + m.divisi_color + '20; color: ' + m.divisi_color" x-text="m.divisi"></span>
                                </td>
                                <td class="py-4 text-gray-700" x-text="m.position"></td>
                                <td class="py-4 text-gray-500" x-text="m.batch"></td>
                                <td class="py-4 text-right space-x-2">
                                    <button @click="selected = m; openEdit = true" class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Edit</button>
                                    <button @click="selected = m; openDelete = true" class="px-3 py-1.5 text-xs text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Hapus</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody x-show="memberFiltered.length === 0">
                        <tr><td colspan="7" class="py-8 text-center text-gray-500">Tidak ada data anggota</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none" x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg" @click.away="openEdit = false">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Edit Anggota</h3>
                <form :action="'/member/update' + selected.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="selected.id">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">NIM</label>
                        <input type="number" name="nim" :value="selected.nim" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" :value="selected.name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Divisi</label>
                        <select name="division_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="1" :selected="selected.divisi === 'Humas'">Humas</option>
                            <option value="2" :selected="selected.divisi === 'Media'">Media</option>
                            <option value="3" :selected="selected.divisi === 'Kegiatan'">Kegiatan</option>
                            <option value="4" :selected="selected.divisi === 'Danus'">Danus</option>
                            <option value="5" :selected="selected.divisi === 'PSDM'">PSDM</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Jabatan</label>
                        <input type="text" name="position" :value="selected.position" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Angkatan</label>
                        <input type="text" name="batch" :value="selected.batch" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Foto Profil (URL)</label>
                        <input type="url" name="photos" :value="selected.photo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none" x-transition.opacity>
            <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-lg" @click.away="openDelete = false">
                <h3 class="mb-2 text-lg font-semibold text-gray-800">Hapus Anggota?</h3>
                <div class="flex items-center gap-3 mb-4">
                    <img :src="selected.photo" :alt="selected.name" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <p class="font-medium text-gray-900" x-text="selected.name"></p>
                        <p class="text-sm text-gray-500" x-text="selected.nim"></p>
                    </div>
                </div>
                <p class="mb-4 text-sm text-gray-500">Data anggota ini akan dihapus permanen.</p>
                <form :action="'/member/delete' + selected.id" method="POST" class="flex justify-end space-x-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDelete = false" class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@php
$members = [
    ['id' => 1, 'nim' => '230101', 'name' => 'Andi Pratama', 'divisi' => 'Humas', 'divisi_color' => '#6366f1', 'position' => 'Koordinator', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Andi+Pratama&background=6366f1&color=fff&size=128'],
    ['id' => 2, 'nim' => '230102', 'name' => 'Budi Santoso', 'divisi' => 'Kegiatan', 'divisi_color' => '#f97316', 'position' => 'Koordinator', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=f97316&color=fff&size=128'],
    ['id' => 3, 'nim' => '230103', 'name' => 'Citra Dewi', 'divisi' => 'Media', 'divisi_color' => '#a855f7', 'position' => 'Staff', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Citra+Dewi&background=a855f7&color=fff&size=128'],
    ['id' => 4, 'nim' => '230104', 'name' => 'Dewi Lestari', 'divisi' => 'Danus', 'divisi_color' => '#22c55e', 'position' => 'Koordinator', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=22c55e&color=fff&size=128'],
    ['id' => 5, 'nim' => '230105', 'name' => 'Eko Prasetyo', 'divisi' => 'PSDM', 'divisi_color' => '#3b82f6', 'position' => 'Staff', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Eko+Prasetyo&background=3b82f6&color=fff&size=128'],
    ['id' => 6, 'nim' => '230106', 'name' => 'Farah Amalia', 'divisi' => 'Humas', 'divisi_color' => '#6366f1', 'position' => 'Staff', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Farah+Amalia&background=6366f1&color=fff&size=128'],
    ['id' => 7, 'nim' => '230107', 'name' => 'Gilang Ramadhan', 'divisi' => 'Kegiatan', 'divisi_color' => '#f97316', 'position' => 'Staff', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Gilang+Ramadhan&background=f97316&color=fff&size=128'],
    ['id' => 8, 'nim' => '230108', 'name' => 'Hana Putri', 'divisi' => 'Media', 'divisi_color' => '#a855f7', 'position' => 'Staff', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Hana+Putri&background=a855f7&color=fff&size=128'],
    ['id' => 9, 'nim' => '230109', 'name' => 'Indra Wijaya', 'divisi' => 'Danus', 'divisi_color' => '#22c55e', 'position' => 'Staff', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Indra+Wijaya&background=22c55e&color=fff&size=128'],
    ['id' => 10, 'nim' => '230110', 'name' => 'Joko Susilo', 'divisi' => 'PSDM', 'divisi_color' => '#3b82f6', 'position' => 'Staff', 'batch' => '2023', 'photo' => 'https://ui-avatars.com/api/?name=Joko+Susilo&background=3b82f6&color=fff&size=128'],
    ['id' => 11, 'nim' => '240101', 'name' => 'Kartika Sari', 'divisi' => 'Humas', 'divisi_color' => '#6366f1', 'position' => 'Anggota', 'batch' => '2024', 'photo' => 'https://ui-avatars.com/api/?name=Kartika+Sari&background=6366f1&color=fff&size=128'],
    ['id' => 12, 'nim' => '240102', 'name' => 'Lukman Hakim', 'divisi' => 'Kegiatan', 'divisi_color' => '#f97316', 'position' => 'Anggota', 'batch' => '2024', 'photo' => 'https://ui-avatars.com/api/?name=Lukman+Hakim&background=f97316&color=fff&size=128'],
    ['id' => 13, 'nim' => '240103', 'name' => 'Maya Indah', 'divisi' => 'Media', 'divisi_color' => '#a855f7', 'position' => 'Anggota', 'batch' => '2024', 'photo' => 'https://ui-avatars.com/api/?name=Maya+Indah&background=a855f7&color=fff&size=128'],
    ['id' => 14, 'nim' => '240104', 'name' => 'Nanda Putra', 'divisi' => 'Danus', 'divisi_color' => '#22c55e', 'position' => 'Anggota', 'batch' => '2024', 'photo' => 'https://ui-avatars.com/api/?name=Nanda+Putra&background=22c55e&color=fff&size=128'],
    ['id' => 15, 'nim' => '240105', 'name' => 'Okta Riani', 'divisi' => 'PSDM', 'divisi_color' => '#3b82f6', 'position' => 'Anggota', 'batch' => '2024', 'photo' => 'https://ui-avatars.com/api/?name=Okta+Riani&background=3b82f6&color=fff&size=128'],
];
@endphp

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('memberTable', () => ({
        members: @json($members),
        openEdit: false,
        openDelete: false,
        selected: {},
        search: '',
        get memberFiltered() {
            if (!this.search) return this.members;
            const s = this.search.toLowerCase();
            return this.members.filter(m =>
                m.name.toLowerCase().includes(s) ||
                m.nim.toString().includes(s) ||
                m.divisi.toLowerCase().includes(s) ||
                m.position.toLowerCase().includes(s) ||
                m.batch.toLowerCase().includes(s)
            );
        }
    }));
});
</script>
@endpush