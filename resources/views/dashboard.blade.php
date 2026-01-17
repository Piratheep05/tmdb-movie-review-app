@extends('layouts.app')

@section('title', 'Dashboard - Movie Review App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-8">
        <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
            Welcome, {{ Auth::user()->name }}! 🎬
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">My Reviews</h2>
                <p class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ Auth::user()->reviews()->count() }}
                </p>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">Total reviews written</p>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Search Movies</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Find and review your favorite movies</p>
                <a href="{{ route('movies.search') }}" class="inline-block px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition">
                    Search Movies
                </a>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">My Reviews</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">View and manage your reviews</p>
                <a href="{{ route('reviews.index', ['user_id' => auth()->id()]) }}" class="inline-block px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition">
                    View All Reviews
                </a>
            </div>
        </div>

        @if(Auth::user()->reviews()->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Recent Reviews</h2>
            <div class="space-y-4">
                @foreach(Auth::user()->reviews()->latest()->take(5)->get() as $review)
                <div class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] pb-4 last:border-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $review->movie_title }}</h3>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">{{ \Illuminate\Support\Str::limit($review->review_text, 100) }}</p>
                            @if($review->rating)
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Rating: {{ $review->rating }}/10</p>
                            @endif
                        </div>
                        <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8 text-center">
            <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4">You haven't written any reviews yet.</p>
            <a href="{{ route('movies.search') }}" class="inline-block px-6 py-3 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition">
                Start Reviewing Movies
            </a>
        </div>
        @endif
    </div>
</div>
@endsection