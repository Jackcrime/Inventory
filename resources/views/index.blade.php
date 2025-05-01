@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-6">
    <div class="max-w-6xl mx-auto">
        <!-- Heading -->
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Dashboard</h1>
            <p class="text-gray-600 text-lg">Selamat datang di Inventaris Admin</p>
        </div>

        <!-- Navigation Cards -->
        <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6">
            <!-- Items Card -->
            <a href="{{ route('items.index') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition group">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 text-blue-600 px-4 py-3 rounded-full">
                        <i class="fas fa-box fa-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold group-hover:text-blue-600">Items</h2>
                        <p class="text-gray-500 text-sm">Lihat dan kelola data item</p>
                    </div>
                </div>
            </a>

            <!-- Categories Card -->
            <a href="{{ route('categories.index') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition group">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 text-green-600 px-4 py-3 rounded-full">
                        <i class="fas fa-tags fa-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold group-hover:text-green-600">Categories</h2>
                        <p class="text-gray-500 text-sm">Kelola kategori item</p>
                    </div>
                </div>
            </a>

            <!-- Locations Card -->
            <a href="{{ route('locations.index') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition group">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 text-purple-600 px-4 py-3 rounded-full">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold group-hover:text-purple-600">Locations</h2>
                        <p class="text-gray-500 text-sm">Atur lokasi penyimpanan</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
