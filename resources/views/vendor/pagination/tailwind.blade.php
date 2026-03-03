@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">

        <div class="flex items-center gap-2 bg-white/70 backdrop-blur-xl shadow-xl px-6 py-4 rounded-2xl border border-gray-200">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                    ←
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md hover:scale-105 transition duration-300">
                    ←
                </a>
            @endif


            {{-- Pagination Elements --}}
            @foreach ($elements as $element)

                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-gray-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold shadow-lg scale-110">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-4 py-2 rounded-xl bg-white text-gray-700 hover:bg-orange-50 hover:scale-105 transition duration-300 shadow-sm">
                                {{ $page }}
                            </a>
                        @endif

                    @endforeach
                @endif

            @endforeach


            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md hover:scale-105 transition duration-300">
                    →
                </a>
            @else
                <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                    →
                </span>
            @endif

        </div>

    </nav>
@endif