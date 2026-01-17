@extends('layouts.app')

@section('title', 'All Reviews - Movie Review App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">All Reviews</h1>
        <a href="{{ route('movies.search') }}" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition">
            Search Movies
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('reviews.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Filter by Movie ID
                </label>
                <input
                    type="number"
                    name="movie_id"
                    value="{{ request('movie_id') }}"
                    placeholder="Enter movie ID"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Filter by User ID
                </label>
                <input
                    type="number"
                    name="user_id"
                    value="{{ request('user_id') }}"
                    placeholder="Enter user ID"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
            </div>
            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="px-6 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition">
                    Filter
                </button>
                @if(request()->has('movie_id') || request()->has('user_id'))
                <a
                    href="{{ route('reviews.index') }}"
                    class="px-6 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#FDFDFC] dark:hover:bg-[#161615] transition">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    @if($reviews->count() > 0)
    <div class="space-y-6">
        @foreach($reviews as $review)
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-2">
                        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                            {{ $review->movie_title }}
                        </h3>
                        <a
                            href="{{ route('movies.show', $review->movie_id) }}"
                            class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:underline">
                            View Movie →
                        </a>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <span>By: <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $review->user->name }}</strong></span>
                        @if($review->rating)
                        <span class="flex items-center gap-1">
                            <span class="text-yellow-500">⭐</span>
                            <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $review->rating }}/10</strong>
                        </span>
                        @endif
                        <span>{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @if(auth()->id() === $review->user_id)
                <div class="flex gap-2">
                    <a
                        href="{{ route('reviews.edit', $review->id) }}"
                        class="px-3 py-1 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:underline">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('reviews.destroy', $review->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="px-3 py-1 text-sm text-red-600 dark:text-red-400 hover:underline"
                            onclick="return confirm('Are you sure you want to delete this review?')">
                            Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
            <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed whitespace-pre-wrap mb-4">
                {{ $review->review_text }}
            </p>
            @if($review->image_path)
            <div class="mt-4">
                <img
                    src="{{ Storage::url($review->image_path) }}"
                    alt="Review image"
                    class="max-w-md rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
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
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8 text-center">
        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">No reviews found.</p>
        @if(request()->has('movie_id') || request()->has('user_id'))
        <a href="{{ route('reviews.index') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:underline">
            Clear filters
        </a>
        @else
        <a href="{{ route('movies.search') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:underline">
            Search for movies to review
        </a>
        @endif
    </div>
    @endif
</div>
@endsection