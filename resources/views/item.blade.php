@extends('layouts.main')

@section('content')
<div x-data="itemComponent()" x-init="init()" class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <!-- Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 border-b border-gray-200 pb-4 items-start">
        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-box text-indigo-600 mr-3"></i> Daftar Item
        </h2>

        <!-- Filter & Tambah -->
        <div class="flex flex-col lg:grid gap-3 w-full">
            <!-- Form -->
            <form id="filter-form"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 col-span-4">
                <input type="text" id="search" name="search" placeholder="Search item..."
                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 w-full lg:w-auto lg:max-w-xs order-1">

                <select id="filter-category"
                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 w-full lg:w-auto lg:max-w-xs order-2">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select id="filter-location"
                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 w-full lg:w-auto lg:max-w-xs order-3">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>

                <div class="grid grid-cols-2 gap-3 order-4 w-full">
                    <button type="submit"
                        class="bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition flex justify-center items-center">
                        <i class="fas fa-search"></i>
                    </button>

                    <button type="button" @click="openModal('create')"
                        class="bg-indigo-500 text-white p-2 rounded-lg shadow-md hover:bg-indigo-600 transition flex justify-center items-center">
                        <i class="fas fa-plus"></i>
                </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-6 text-left whitespace-nowrap">Name</th>
                    <th class="py-3 px-6 text-left whitespace-nowrap">Category</th>
                    <th class="py-3 px-6 text-left whitespace-nowrap">Location</th>
                    <th class="py-3 px-6 text-left whitespace-nowrap">Qty</th>
                    <th class="py-3 px-6 text-left whitespace-nowrap">Description</th>
                    <th class="py-3 px-6 text-center whitespace-nowrap">Status</th>
                    <th class="py-3 px-6 text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody id="item-table-body" class="bg-white"></tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination" class="mt-4 flex justify-center"></div>

    <!-- Modal -->
    <template x-if="modalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="closeModal">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 relative" @click.stop>
                <button @click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl px-4 py-2">✖</button>
                <h2 class="text-xl font-semibold mb-4" x-text="modalTitle"></h2>

                <<!-- Modal Create/Edit -->
                    <template x-if="modalOpen && (modalMode === 'create' || modalMode === 'edit')">
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="closeModal">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 relative" @click.stop>
                                <button @click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl px-4 py-2">✖</button>
                                <h2 class="text-xl font-semibold mb-4" x-text="modalTitle"></h2>

                                <form @submit.prevent="submitForm">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold">Nama Item</label>
                                            <input type="text" x-model="formData.name" class="w-full border border-gray-200 rounded px-4 py-2 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold">Kategori</label>
                                            <select x-model="formData.categories_id" class="w-full border border-gray-200 rounded px-4 py-2 bg-white">
                                                <option value="">Pilih Kategori</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold">Lokasi</label>
                                            <select x-model="formData.locations_id" class="w-full border border-gray-200 rounded px-4 py-2 bg-white">
                                                <option value="">Pilih Lokasi</option>
                                                @foreach($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold">Jumlah</label>
                                            <input type="number" x-model="formData.quantity" class="w-full border border-gray-200 rounded px-4 py-2 bg-white">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-semibold">Deskripsi</label>
                                            <textarea x-model="formData.description" class="w-full border border-gray-200 rounded px-4 py-2 bg-white"></textarea>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>
                    <!-- Modal Show -->
                    <template x-if="modalOpen && modalMode === 'show'">
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="closeModal">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 relative" @click.stop>
                                <button @click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl px-4 py-2">✖</button>
                                <h2 class="text-xl font-semibold mb-4">Detail Item</h2>

                                <div class="space-y-3">
                                    <p><strong>Nama:</strong> <span x-text="formData.name"></span></p>
                                    <p><strong>Kategori ID:</strong> <span x-text="formData.categories_id"></span></p>
                                    <p><strong>Lokasi ID:</strong> <span x-text="formData.locations_id"></span></p>
                                    <p><strong>Jumlah:</strong> <span x-text="formData.quantity"></span></p>
                                    <p><strong>Deskripsi:</strong> <span x-text="formData.description || '-'"></span></p>
                                </div>
                            </div>
                        </div>
                    </template>
            </div>
        </div>
    </template>

</div>

<script>
    function itemComponent() {
        return {
            modalOpen: false,
            modalMode: 'create',
            modalTitle: 'Tambah Item',
            modalAction: '{{ route("items.store") }}',
            formData: {
                name: '',
                categories_id: '',
                locations_id: '',
                quantity: '',
                description: ''
            },
            init() {
                loadItems();
                window.itemComponentInstance = this;

            },
            openModal(mode, data = null) {
                this.modalMode = mode;
                this.modalOpen = true;

                const defaultData = {
                    id: null,
                    name: '',
                    categories_id: '',
                    locations_id: '',
                    quantity: '',
                    description: ''
                };

                if (mode === 'create') {
                    this.modalTitle = 'Tambah Item';
                    this.modalAction = '{{ route("items.store") }}';
                    this.formData = {
                        ...defaultData
                    };
                }

                if (mode === 'edit' && data) {
                    this.modalTitle = 'Edit Item';
                    this.modalAction = `/items/${data.id}`;
                    this.formData = {
                        id: data.id,
                        name: data.name,
                        categories_id: data.categories_id,
                        locations_id: data.locations_id,
                        quantity: data.quantity,
                        description: data.description
                    };
                }

                if (mode === 'show' && data) {
                    this.modalTitle = 'Detail Item';
                    this.formData = {
                        id: data.id,
                        name: data.name,
                        categories_id: data.categories_id,
                        locations_id: data.locations_id,
                        quantity: data.quantity,
                        description: data.description
                    };
                }
            },
            closeModal() {
                this.modalOpen = false;
                this.modalMode = '';
                this.modalTitle = '';
                this.modalAction = '';
            },
            async submitForm() {
                try {
                    const method = this.modalMode === 'edit' ? 'PUT' : 'POST';
                    const response = await fetch(this.modalAction, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.formData)
                    });

                    if (!response.ok) throw new Error('Gagal menyimpan item');

                    const result = await response.json();

                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    this.closeModal();
                    loadItems();
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
            }
        }
    }

    function deleteItem(id) {
        Swal.fire({
            title: 'Hapus Item?',
            text: 'Item akan dipindahkan ke sampah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/items/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        loadItems();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus item', 'error');
                    }
                });
            }
        });
    }


    function restoreItem(id) {
        Swal.fire({
            title: 'Pulihkan Item?',
            text: 'Item akan dipulihkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, pulihkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/items/restore/${id}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dipulihkan',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        loadItems();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal memulihkan item', 'error');
                    }
                });
            }
        });
    }


    function forceDeleteItem(id) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: 'Data tidak bisa dikembalikan setelah dihapus!',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#4b5563',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus permanen!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/items/force-delete/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus Permanen',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        loadItems();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus permanen item', 'error');
                    }
                });
            }
        });
    }

    function loadItems(page = 1) {
        const search = $('#search').val();
        const category = $('#filter-category').val();
        const location = $('#filter-location').val();
        $.get('{{ Route ("items.json") }}', {
                search: search,
                category: category,
                location: location,
                page: page
            },
            function(response) {
                let rows = '';
                response.data.forEach((item, index) => {
                    const status = item.deleted_at ?
                        '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">Terhapus</span>' :
                        '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-600">Aktif</span>';

                    const safeData = JSON.stringify(item).replace(/"/g, '&quot;');

                    const actions = item.deleted_at ?
                        `
                    <button onclick="restoreItem(${item.id})" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition" title="Pulihkan">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                    <button onclick="forceDeleteItem(${item.id})" class="bg-gray-700 text-white px-3 py-1 rounded-lg hover:bg-gray-800 transition" title="Hapus Permanen">
                        <i class="fas fa-trash"></i>
                    </button>
                ` :
                        `
                    <button onclick='window.itemComponentInstance.openModal("edit", ${safeData})' class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick='window.itemComponentInstance.openModal("show", ${safeData})' class="bg-indigo-500 text-white px-3 py-1 rounded-lg hover:bg-indigo-600 transition">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="deleteItem(${item.id})" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `;
                    rows += `
                <tr>
                    <td class="px-5 py-3">${item.name}</td>
                    <td class="px-5 py-3">${item.category?.name || '-'}</td>
                    <td class="px-5 py-3">${item.location?.name || '-'}</td>
                    <td class="px-5 py-3">${item.quantity}</td>
                    <td class="px-5 py-3">${item.description || '-'}</td>
                    <td class="px-5 py-5 text-center">${status}</td>
                    <td class="px-5 py-3 flex gap-2 justify-center">${actions}</td>
                </tr>
            `;
                });

                $('#item-table-body').html(rows);
                $('#pagination').html(response.links);

                $('#pagination a').on('click', function(e) {
                    e.preventDefault();
                    const url = new URL($(this).attr('href'));
                    const page = url.searchParams.get('page');
                    loadItems(page);
                });
            });
    }

    $(document).ready(function() {
        loadItems();
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            loadItems(1);
        });
    });
</script>
@endsection