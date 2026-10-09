@extends('components.layouts.app')

@section('title', 'Kelola Berita')

@use(App\Models\News)

@section('content')
    <div x-data="newsCrud()" class="relative">

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
                <h2 class="text-2xl font-bold text-gray-800">Kelola Berita</h2>
                <p class="text-sm text-gray-500">Tulis dan publikasikan berita SEMAFE — tampil di <a
                        href="{{ url('/news') }}" class="text-indigo-600 hover:underline">halaman publik</a></p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="search" placeholder="Cari berita..."
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
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Diarsipkan</option>
                </select>

                <button @click="resetForm(); openInsert = true" type="button"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl hover:text-white hover:bg-indigo-600 hover:border-indigo-600 shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-200 cursor-pointer">
                    <span
                        class="flex items-center justify-center w-5 h-5 rounded-md bg-indigo-100 group-hover:bg-white/20 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                    Tulis Berita
                </button>
            </div>
        </div>

        {{-- List --}}
        <div class="space-y-4" x-show="filtered.length > 0">
            <template x-for="n in filtered" :key="n.id">
                <article
                    class="flex flex-col sm:flex-row gap-5 p-5 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <img :src="n.thumbnail" :alt="n.title"
                        class="w-full sm:w-48 h-40 sm:h-28 rounded-lg object-cover shrink-0">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                :class="statusClass(n.status)" x-text="statusLabel(n.status)"></span>
                            <span class="text-xs text-gray-400" x-text="n.date_display"></span>
                        </div>

                        <h3 class="mt-2 font-bold text-gray-900 line-clamp-1" x-text="n.title"></h3>
                        <p class="mt-1 text-sm text-gray-500 line-clamp-2" x-text="n.excerpt"></p>

                        <div class="flex items-center gap-2 mt-3 text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span x-text="n.author"></span>
                        </div>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end gap-2">
                        <a :href="'/news/' + n.slug" target="_blank"
                            class="px-3 py-1.5 text-xs text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">Lihat</a>
                        <button @click="edit(n)"
                            class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">Edit</button>
                        <button @click="selected = n; openDelete = true"
                            class="px-3 py-1.5 text-xs text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">Hapus</button>
                    </div>
                </article>
            </template>
        </div>

        <div x-show="filtered.length === 0"
            class="p-12 bg-white rounded-xl shadow-sm border border-gray-100 text-center text-gray-500">
            Belum ada berita
        </div>

        {{-- Modal Tambah/Edit --}}
        <div x-show="openInsert || openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-2xl p-6 bg-white rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto"
                @click.away="openInsert = false; openEdit = false">

                <h3 class="mb-5 text-lg font-semibold text-gray-800" x-text="openEdit ? 'Edit Berita' : 'Tulis Berita Baru'"></h3>

                <form :action="action" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="openEdit"><input type="hidden" name="_method" value="PATCH"></template>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Judul Berita</label>
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
                                <option value="archived">Diarsipkan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Isi Berita</label>
                        <textarea name="content" x-model="form.content" rows="7" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">URL Thumbnail</label>
                            <input type="url" name="thumbnail" x-model="form.thumbnail" required
                                placeholder="https://..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal Publikasi</label>
                            <input type="datetime-local" name="published_at" x-model="form.published_at" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <template x-if="form.thumbnail">
                        <div>
                            <p class="block text-sm text-gray-600 mb-1">Pratinjau thumbnail</p>
                            <img :src="form.thumbnail" alt="Pratinjau"
                                class="h-32 w-full rounded-lg object-cover border border-gray-200">
                        </div>
                    </template>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="openInsert = false; openEdit = false"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer"
                            x-text="openEdit ? 'Simpan Perubahan' : 'Publikasikan'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Hapus --}}
        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display:none" x-transition.opacity>
            <div class="w-full max-w-sm p-6 bg-white rounded-2xl shadow-xl" @click.away="openDelete = false">

                <h3 class="mb-2 text-lg font-semibold text-gray-800">Hapus Berita?</h3>
                <p class="mb-4 text-sm text-gray-500">Berita <span x-text="selected.title"
                        class="font-medium text-red-600"></span> akan dihapus permanen.</p>

                <form :action="'/admin/news/delete/' + selected.id" method="POST" class="flex justify-end space-x-2">
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
    $newsList = News::with('users')
        ->latest('published_at')
        ->get()
        ->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'slug' => $n->slug,
                'content' => $n->content,
                'excerpt' => \Illuminate\Support\Str::limit(strip_tags($n->content), 120),
                'thumbnail' => $n->thumbnail ?: 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=85',
                'published_at' => optional($n->published_at)->format('Y-m-d\TH:i') ?? '',
                'date_display' => optional($n->published_at)->translatedFormat('d M Y') ?? '-',
                'author' => $n->users?->name ?? 'Admin',
                'status' => $n->status,
            ];
        })
        ->values();
@endphp

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('newsCrud', () => ({
                news: @json($newsList),
                openInsert: false,
                openEdit: false,
                openDelete: false,
                selected: {},
                form: {},
                search: '',
                statusFilter: '',
                toast: { show: false, message: '' },

                get action() {
                    return this.openEdit ? '/admin/news/update/' + this.selected.id : '{{ route('admin.news.store') }}';
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
                        content: '',
                        thumbnail: '',
                        published_at: new Date().toISOString().slice(0, 16),
                        status: 'published',
                    };
                    this.openEdit = false;
                },

                edit(n) {
                    this.selected = n;
                    this.form = { ...n, published_at: n.published_at || '' };
                    this.openEdit = true;
                    this.openInsert = false;
                },

                statusClass(status) {
                    return {
                        'bg-green-100 text-green-700': status === 'published',
                        'bg-gray-100 text-gray-600': status === 'draft',
                        'bg-yellow-100 text-yellow-700': status === 'archived',
                    }[status] || 'bg-gray-100 text-gray-600';
                },

                statusLabel(status) {
                    return {
                        published: 'Published',
                        draft: 'Draft',
                        archived: 'Diarsipkan',
                    }[status] || status;
                },

                get filtered() {
                    let list = this.news;

                    if (this.statusFilter) {
                        list = list.filter(n => n.status === this.statusFilter);
                    }

                    if (!this.search) return list;

                    const s = this.search.toLowerCase();

                    return list.filter(n =>
                        n.title.toLowerCase().includes(s) ||
                        n.slug.toLowerCase().includes(s) ||
                        n.content.toLowerCase().includes(s)
                    );
                }
            }));
        });
    </script>
@endpush