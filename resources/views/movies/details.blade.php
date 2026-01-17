@extends('layouts.app')

@section('title', ($movie['title'] ?? 'Movie') . ' - Movie Review App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <a href="{{ route('movies.search') }}" class="inline-flex items-center text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] mb-6 transition">
        ← Back to Search
    </a>

    <!-- Movie Header -->
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="md:flex">
            @if(isset($movie['poster_url']))
            <div class="md:w-1/3">
                <img
                    src="{{ $movie['poster_url'] }}"
                    alt="{{ $movie['title'] ?? 'Movie poster' }}"
                    class="w-full h-auto object-cover"
                    onerror="this.src='https://via.placeholder.com/500x750?text=No+Image'">
            </div>
            @endif
            <div class="md:w-2/3 p-6 md:p-8">
                <h1 class="text-3xl md:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    {{ $movie['title'] ?? 'Unknown Movie' }}
                </h1>

                <div class="flex flex-wrap gap-4 mb-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    @if(isset($movie['release_date']))
                    <span>{{ \Carbon\Carbon::parse($movie['release_date'])->format('F j, Y') }}</span>
                    @endif
                    @if(isset($movie['runtime']))
                    <span>{{ $movie['runtime'] }} minutes</span>
                    @endif
                    @if(isset($movie['genres']) && is_array($movie['genres']))
                    <span>
                        {{ collect($movie['genres'])->pluck('name')->join(', ') }}
                    </span>
                    @endif
                </div>

                @if(isset($movie['vote_average']))
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-yellow-500 text-xl">⭐</span>
                    <span class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                        {{ number_format($movie['vote_average'], 1) }}/10
                    </span>
                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        ({{ number_format($movie['vote_count'] ?? 0) }} votes)
                    </span>
                </div>
                @endif

                @if($averageRating > 0)
                <div class="mb-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">Average User Rating:</p>
                    <div class="flex items-center gap-2">
                        <span class="text-yellow-500">⭐</span>
                        <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                            {{ $averageRating }}/10
                        </span>
                        <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            ({{ $reviews->total() }} review{{ $reviews->total() !== 1 ? 's' : '' }})
                        </span>
                    </div>
                </div>
                @endif

                @if(isset($movie['overview']))
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Overview</h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                        {{ $movie['overview'] }}
                    </p>
                </div>
                @endif

                @if(isset($movie['credits']['cast']) && count($movie['credits']['cast']) > 0)
                <div>
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Cast</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        {{ collect($movie['credits']['cast'])->take(5)->pluck('name')->join(', ') }}
                        @if(count($movie['credits']['cast']) > 5)
                        and {{ count($movie['credits']['cast']) - 5 }} more
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Review Section -->
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                Reviews ({{ $reviews->total() }})
            </h2>

            @if(!$userReview)
            <a
                href="{{ route('reviews.create', $movie['id']) }}"
                class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition">
                Write a Review
            </a>
            @else
            <a
                href="{{ route('reviews.edit', $userReview->id) }}"
                class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition">
                Edit Your Review
            </a>
            @endif
        </div>

        @if($reviews->count() > 0)
        <div class="space-y-6">
            @foreach($reviews as $review)
            <div class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] pb-6 last:border-0">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                            {{ $review->user->name }}
                        </h3>
                        @if($review->rating)
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-yellow-500">⭐</span>
                            <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                {{ $review->rating }}/10
                            </span>
                        </div>
                        @endif
                    </div>
                    <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                        {{ $review->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed whitespace-pre-wrap mb-4">
                    {{ $review->review_text }}
                </p>
                @if($review->image_path)
                <div class="mt-4 mb-4">
                    <img
                        src="{{ Storage::url($review->image_path) }}"
                        alt="Review image"
                        class="max-w-md rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                </div>
                @endif
                @if(auth()->id() === $review->user_id)
                <div class="mt-3 flex gap-2">
                    <a
                        href="{{ route('reviews.edit', $review->id) }}"
                        class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:underline">
                        Edit
                    </a>
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">|</span>
                    <form method="POST" action="{{ route('reviews.destroy', $review->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="text-sm text-red-600 dark:text-red-400 hover:underline"
                            onclick="return confirm('Are you sure you want to delete this review?')">
                            Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
        @else
        <p class="text-[#706f6c] dark:text-[#A1A09A] text-center py-8">
            No reviews yet. Be the first to review this movie!
        </p>
        @endif
    </div>
</div>
@endsection