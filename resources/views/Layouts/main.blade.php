<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: window.innerWidth >= 640, toggleSidebar() { this.sidebarOpen = !this.sidebarOpen } }" @resize.window="sidebarOpen = window.innerWidth >= 640">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miracle Inventory</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">
  <div
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 backdrop-blur-none"
    x-transition:enter-end="opacity-100 backdrop-blur-sm"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 backdrop-blur-sm"
    x-transition:leave-end="opacity-0 backdrop-blur-none"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-20 lg:hidden"
    @click="sidebarOpen = false">
  </div>

  <!-- Main layout -->
  <div class="flex flex-col lg:flex-row min-h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed lg:relative z-30 lg:z-0 top-0 left-0 w-64 bg-blue-700 text-white transform transition-transform duration-300 ease-in-out h-full lg:h-auto lg:translate-x-0 lg:flex-shrink-0">

      <!-- Sidebar header -->
      <div class="h-16 flex items-center justify-between px-4 border-b border-blue-600">
        <div class="text-2xl font-bold">Miracle</div>
        <button
          @click="sidebarOpen = false"
          class="lg:hidden text-white text-xl">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Sidebar links -->
      <nav class="flex-1 p-4 space-y-4">
        <a href="{{ route('index') }}" @class([ 'flex items-center py-2 px-4 rounded-lg hover:bg-blue-600 transition' , 'bg-blue-600 font-semibold'=> Request::routeIs('index')
          ])>
          <i class="fa-solid fa-house mr-2"></i> Home
        </a>
        <a href="{{ route('items.index') }}" @class([ 'flex items-center py-2 px-4 rounded-lg hover:bg-blue-600 transition' , 'bg-blue-600 font-semibold'=> Request::routeIs('items.index')
          ])>
          <i class="fa-solid fa-boxes-stacked mr-2"></i> Inventory
        </a>
        <a href="{{ route('categories.index') }}" @class([ 'flex items-center py-2 px-4 rounded-lg hover:bg-blue-600 transition' , 'bg-blue-600 font-semibold'=> Request::routeIs('categories.index')
          ])>
          <i class="fa-solid fa-layer-group mr-2"></i> Categories
        </a>
        <a href="{{ route('locations.index') }}" @class([ 'flex items-center py-2 px-4 rounded-lg hover:bg-blue-600 transition' , 'bg-blue-600 font-semibold'=> Request::routeIs('locations.index')
          ])>
          <i class="fa-solid fa-location-dot mr-2"></i> Locations
        </a>
      </nav>
    </aside>
    <!-- Content -->
    <div class="flex-1 flex flex-col w-full overflow-x-hidden">
      <!-- Top navbar -->
      <header class="flex items-center justify-between bg-white h-16 px-4 shadow-md">
        <button @click="sidebarOpen = true" class="lg:hidden text-blue-700 text-2xl">
          <i class="fas fa-bars"></i>
        </button>
      </header>
      <!-- Main content -->
      <main class="flex-1 overflow-y-auto p-4 md:p-6 max-w-full">
        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>
