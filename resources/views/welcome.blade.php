@extends('layouts.app')

@section('title', 'Welcome - Movie Review App')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                🎬 Movie Review App
            </h1>
            <p class="text-xl text-[#706f6c] dark:text-[#A1A09A] mb-8">
                Discover, review, and share your thoughts on your favorite movies
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <div class="text-3xl mb-3">🔍</div>
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Search Movies</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Find movies from The Movie Database (TMDB) with detailed information
                </p>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <div class="text-3xl mb-3">⭐</div>
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Write Reviews</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Share your thoughts and rate movies with detailed reviews
                </p>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <div class="text-3xl mb-3">📊</div>
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Track Analytics</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    View your review statistics and rating distributions
                </p>
            </div>
        </div>

        <div class="text-center">
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded-lg hover:opacity-90 transition font-medium text-lg">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-8 py-3 bg-white dark:bg-[#0a0a0a] border-2 border-[#1b1b18] dark:border-[#EDEDEC] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#FDFDFC] dark:hover:bg-[#161615] transition font-medium text-lg">
                    Register
                </a>
            </div>
        </div>
    </div>
</div>
@endsection