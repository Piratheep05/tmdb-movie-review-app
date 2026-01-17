@extends('layouts.app')

@section('title', 'Edit Review - Movie Review App')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Edit Your Review</h1>

    <!-- Movie Info -->
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center gap-4">
            @if(isset($movie['poster_path']))
            <img
                src="{{ app(\App\Services\TmdbService::class)->getPosterUrl($movie['poster_path']) }}"
                alt="{{ $movie['title'] ?? 'Movie poster' }}"
                class="w-20 h-30 object-cover rounded"
                onerror="this.src='https://via.placeholder.com/200x300?text=No+Image'">
            @endif
            <div>
                <h2 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $movie['title'] ?? 'Unknown Movie' }}
                </h2>
                @if(isset($movie['release_date']))
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    {{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}
                </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Review Form -->
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
        <form method="POST" action="{{ route('reviews.update', $review->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="movie_id" value="{{ $review->movie_id }}">
            <input type="hidden" name="movie_title" value="{{ $review->movie_title }}">

            <div class="mb-6">
                <label for="rating" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Rating (1-10) <span class="text-[#706f6c] dark:text-[#A1A09A]">(Optional)</span>
                </label>
                <select
                    id="rating"
                    name="rating"
                    class="w-full md:w-32 px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
                    <option value="">No rating</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>
                        {{ $i }}/10
                        </option>
                        @endfor
                </select>
                @error('rating')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="review_text" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Your Review <span class="text-[#706f6c] dark:text-[#A1A09A]">(Min 10 characters)</span>
                </label>
                <textarea
                    id="review_text"
                    name="review_text"
                    rows="8"
                    required
                    minlength="10"
                    maxlength="5000"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                    placeholder="Share your thoughts about this movie...">{{ old('review_text', $review->review_text) }}</textarea>
                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <span id="char-count">{{ strlen(old('review_text', $review->review_text)) }}</span> / 5000 characters
                </p>
                @error('review_text')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button
                    type="submit"
                    class="px-6 py-3 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition font-medium">
                    Update Review
                </button>
                <a
                    href="{{ route('movies.show', $review->movie_id) }}"
                    class="px-6 py-3 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#FDFDFC] dark:hover:bg-[#161615] transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const textarea = document.getElementById('review_text');
    const charCount = document.getElementById('char-count');

    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
</script>
@endpush
@endsection