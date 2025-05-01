@extends('layouts.main')

@section('content')
<div x-data="locationComponent()" x-init="init()" class="max-w-7xl mx-auto bg-white p-6 md:p-8 rounded-xl shadow-lg">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-200 pb-4 gap-4">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i> Daftar Lokasi
        </h2>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <form id="searchForm" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <input type="text" id="searchInput" placeholder="Search locations..." class="px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full hover:bg-blue-700 sm:w-auto">Search</button>
            </form>
            <button @click="openModal('create')" class="flex items-center justify-center bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition gap-2 w-full sm:w-auto">
                <i class="fas fa-plus-circle"></i> <span>Tambah Lokasi</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-5 py-3 text-left whitespace-nowrap">No</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap">Nama</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap">Deskripsi</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap">Status</th>
                    <th class="px-5 py-3 text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody id="location-table-body" class="bg-white">
                <!-- Data will be populated here by JavaScript -->
            </tbody>
        </table>
    </div>

    <div id="pagination" class="mt-4 flex justify-center"></div>
    <!-- Modal -->
    <template x-if="modalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="closeModal">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 relative" @click.stop>
            <button @click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl px-4 py-2">✖</button>
                <h2 class="text-xl font-semibold mb-4" x-text="modalTitle"></h2>

                <template x-if="modalMode !== 'show'">
                    <form :action="modalAction" method="POST" @submit.prevent="submitForm">
                        <template x-if="modalMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-semibold">Nama Lokasi</label>
                            <input type="text" name="name" class="w-full border px-4 py-2 rounded-lg focus:ring-blue-500" x-model="formData.name" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold">Deskripsi</label>
                            <textarea name="description" class="w-full border px-4 py-2 rounded-lg focus:ring-blue-500" x-model="formData.description" required></textarea>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                        </div>
                    </form>
                </template>

                <template x-if="modalMode === 'show'">
                    <div>
                        <p><strong>Nama:</strong> <span x-text="formData.name"></span></p>
                        <p class="mt-2"><strong>Deskripsi:</strong> <span x-text="formData.description"></span></p>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

<script>
function locationComponent() {
        return {
            init() {
                window.locationComponentInstance = this;
            },
            modalOpen: false,
            modalMode: '',
            modalTitle: '',
            modalAction: '',
            formData: {
                id: null,
                name: '',
                description: ''
            },

            openModal(mode, data = null) {
                this.modalMode = mode;
                this.modalOpen = true;

                if (mode === 'create') {
                    this.modalTitle = 'Tambah Lokasi';
                    this.modalAction = '{{ route("locations.store") }}';
                    this.formData = {
                        id: null,
                        name: '',
                        description: ''
                    };
                } else if (mode === 'edit') {
                    this.modalTitle = 'Edit Lokasi';
                    this.modalAction = `/locations/${data.id}`;
                    this.formData = {
                        id: data.id,
                        name: data.name,
                        description: data.description
                    };
                } else if (mode === 'show') {
                    this.modalTitle = 'Detail Lokasi';
                    this.formData = {
                        id: data.id,
                        name: data.name,
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
                    const url = this.modalAction;

                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: this.formData.name,
                            description: this.formData.description
                        })
                    });

                    if (!response.ok) throw new Error('Gagal menyimpan Lokasi');

                    const result = await response.json();

                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    this.closeModal();
                    loadLocations();

                } catch (error) {
                    Swal.fire('Error', error.message, 'error');
                }
            }
        }
    }

    function deleteLocation(id) {
        Swal.fire({
            title: 'Hapus Lokasi?',
            text: 'Lokasi akan dipindahkan ke sampah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/locations/${id}`,
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
                        loadLocations();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus Lokasi', 'error');
                    }
                });
            }
        });
    }


    function restoreLocation(id) {
        Swal.fire({
            title: 'Pulihkan Lokasi?',
            text: 'Lokasi akan dipulihkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, pulihkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/locations/restore/${id}`,
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
                        loadLocations();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal memulihkan Lokasi', 'error');
                    }
                });
            }
        });
    }


    function forceDeleteLocation(id) {
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
                    url: `/locations/force-delete/${id}`,
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
                        loadLocations();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus permanen Lokasi', 'error');
                    }
                });
            }
        });
    }



    function loadLocations(search = '', page = 1) {
        $.get('{{ route("locations.json") }}', {
            search: search,
            page: page
        }, function(response) {
            let rows = '';
            response.data.forEach((location, index) => {
                const status = location.deleted_at ?
                    '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">Terhapus</span>' :
                    '<span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-600">Aktif</span>';

                const safeData = JSON.stringify(location).replace(/"/g, '&quot;');

                const actions = location.deleted_at ?
                    `
                    <button onclick="restoreLocation(${location.id})" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition" title="Pulihkan">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                    <button onclick="forceDeleteLocation(${location.id})" class="bg-gray-700 text-white px-3 py-1 rounded-lg hover:bg-gray-800 transition" title="Hapus Permanen">
                        <i class="fas fa-trash"></i>
                    </button>
                ` :
                    `
                    <button onclick='window.locationComponentInstance.openModal("edit", ${safeData})' class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick='window.locationComponentInstance.openModal("show", ${safeData})' class="bg-indigo-500 text-white px-3 py-1 rounded-lg hover:bg-indigo-600 transition">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="deleteLocation(${location.id})" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `;

                rows += `
                <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                    <td class="px-5 py-3">${index + 1}</td>
                    <td class="px-5 py-3">${location.name}</td>
                    <td class="px-5 py-3">${location.description ?? '-'}</td>
                    <td class="px-5 py-3">${status}</td>
                    <td class="px-5 py-3 flex gap-2 justify-center">${actions}</td>
                </tr>
            `;
            });

            $('#location-table-body').html(rows);
            $('#pagination').html(response.links);

            // Tambahkan event click ke link pagination agar pakai AJAX
            $('#pagination a').on('click', function(e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'));
                const page = url.searchParams.get('page');
                const search = $('#searchInput').val();
                loadLocations(search, page);
            });
        });
    }

    $(document).ready(function() {
        loadLocations();

        $('#searchForm').submit(function(e) {
            e.preventDefault();
            const search = $('#searchInput').val();
            loadLocations(search);
        });
    });
</script>
@endsection
