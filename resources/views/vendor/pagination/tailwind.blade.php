@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center p-6">
    <div class="flex flex-wrap items-center gap-2 bg-gray-800 dark:bg-gray-900 px-6 py-3 rounded-full shadow-md">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-full cursor-not-allowed">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-300 bg-gray-800 dark:bg-gray-800 rounded-full hover:bg-blue-100 dark:hover:bg-gray-700 transition duration-300">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-full">
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="flex items-center justify-center w-10 h-10 text-white bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full font-bold shadow-lg">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="flex items-center justify-center w-10 h-10 text-gray-100 dark:text-gray-300 bg-gray-800 dark:bg-gray-800 rounded-full hover:bg-blue-100 dark:hover:bg-gray-700 transition duration-300">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center w-10 h-10 text-gray-600 dark:text-gray-300 bg-gray-800 dark:bg-gray-800 rounded-full hover:bg-blue-100 dark:hover:bg-gray-700 transition duration-300">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 6l6 6-6 6" />
                </svg>
            </a>
        @else
            <span class="flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-full cursor-not-allowed">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 6l6 6-6 6" />
                </svg>
            </span>
        @endif

    </div>
</nav>
@endif
