@extends('components.layouts.app')

@section('title', 'Anggota')

@section('content')
    <div x-data="{ openEdit: false, openDelete: false, selected: {} }">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Anggota</h2>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-500 border-b">
                            <th class="pb-2 font-medium">NIM</th>
                            <th class="pb-2 font-medium">Nama</th>
                            <th class="pb-2 font-medium">Divisi</th>
                            <th class="pb-2 font-medium">Jabatan</th>
                            <th class="pb-2 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ([['id' => 1, 'nim' => 230101, 'name' => 'Andi Pratama', 'division' => 'Humas', 'position' => 'Anggota'], ['id' => 2, 'nim' => 230105, 'name' => 'Siti Rahma', 'division' => 'Media', 'position' => 'Koordinator']] as $member)
                            <tr>
                                <td class="py-3">{{ $member['nim'] }}</td>
                                <td class="py-3">{{ $member['name'] }}</td>
                                <td class="py-3">{{ $member['division'] }}</td>
                                <td class="py-3">{{ $member['position'] }}</td>
                                <td class="py-3 text-right space-x-2">
                                    <button @click="selected = {{ Js::from($member) }}; openEdit = true"
                                        class="px-3 py-1 text-xs text-white bg-indigo-600 rounded hover:bg-indigo-700">Edit</button>
                                    <button @click="selected = {{ Js::from($member) }}; openDelete = true"
                                        class="px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none"
            x-transition.opacity>
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg" @click.away="openEdit = false">
                <h3 class="mb-4 text-lg font-semibold">Edit Anggota</h3>
                <form :action="'/member/update' + selected.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm text-gray-600">NIM</label>
                        <input type="number" name="nim" :value="selected.nim" class="w-full mt-1 border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Nama</label>
                        <input type="text" name="name" :value="selected.name" class="w-full mt-1 border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Jabatan</label>
                        <input type="text" name="position" :value="selected.position" class="w-full mt-1 border-gray-300 rounded-lg">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none"
            x-transition.opacity>
            <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-lg" @click.away="openDelete = false">
                <h3 class="mb-2 text-lg font-semibold">Hapus Anggota?</h3>
                <p class="mb-4 text-sm text-gray-500">Data <span x-text="selected.name" class="font-medium"></span> akan dihapus permanen.</p>
                <form :action="'/member/delete' + selected.id" method="POST" class="flex justify-end space-x-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDelete = false" class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg">Hapus</button>
                </form>
            </div>
        </div>
    </div>
@endsection
