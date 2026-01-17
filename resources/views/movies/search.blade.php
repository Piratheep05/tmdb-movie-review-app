@extends('layouts.app')

@section('title', 'Search Movies - Movie Review App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Search Movies</h1>

    <!-- Search Form -->
    <form method="GET" action="{{ route('movies.search') }}" class="mb-8">
        <div class="flex gap-4">
            <input
                type="text"
                name="q"
                value="{{ $query }}"
                placeholder="Search for movies..."
                class="flex-1 px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                autofocus>
            <button
                type="submit"
                class="px-6 py-3 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition font-medium">
                Search
            </button>
        </div>
    </form>

    @if(isset($error))
    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
        {{ $error }}
    </div>
    @endif

    @if($query)
    @if($totalResults > 0)
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
        Found {{ $totalResults }} result(s) for "{{ $query }}"
    </p>

    <!-- Movie Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach($movies as $movie)
        <a href="{{ route('movies.show', $movie['id']) }}" class="group">
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                @if(isset($movie['poster_url']))
                <img
                    src="{{ $movie['poster_url'] }}"
                    alt="{{ $movie['title'] ?? 'Movie poster' }}"
                    class="w-full aspect-[2/3] object-cover group-hover:scale-105 transition-transform duration-300"
                    onerror="this.src='https://via.placeholder.com/500x750?text=No+Image'">
                @else
                <div class="w-full aspect-[2/3] bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-gray-400 dark:text-gray-500 text-sm">No Image</span>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-1 line-clamp-2 group-hover:text-[#f53003] dark:group-hover:text-[#FF4433] transition">
                        {{ $movie['title'] ?? 'Unknown' }}
                    </h3>
                    @if(isset($movie['release_date']))
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        {{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}
                    </p>
                    @endif
                    @if(isset($movie['vote_average']))
                    <div class="flex items-center gap-1 mt-2">
                        <span class="text-yellow-500">⭐</span>
                        <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            {{ number_format($movie['vote_average'], 1) }}/10
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($totalPages > 1)
    <div class="mt-8 flex justify-center gap-2">
        @if($currentPage > 1)
        <a
            href="{{ route('movies.search', ['q' => $query, 'page' => $currentPage - 1]) }}"
            class="px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition">
            Previous
        </a>
        @endif

        <span class="px-4 py-2 text-[#706f6c] dark:text-[#A1A09A]">
            Page {{ $currentPage }} of {{ $totalPages }}
        </span>

        @if($currentPage < $totalPages)
            <a
            href="{{ route('movies.search', ['q' => $query, 'page' => $currentPage + 1]) }}"
            class="px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition">
            Next
            </a>
            @endif
    </div>
    @endif
    @else
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8 text-center">
        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">No movies found for "{{ $query }}"</p>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Try a different search term.</p>
    </div>
    @endif
    @else
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8 text-center">
        <p class="text-[#706f6c] dark:text-[#A1A09A]">Enter a movie title to search</p>
    </div>
    @endif
</div>
@endsection