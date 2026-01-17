@extends('layouts.app')

@section('title', 'Login - Movie Review App')

@section('content')
<div class="max-w-md mx-auto px-4">
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-[#1b1b18] dark:text-[#EDEDEC]">Login</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                    placeholder="your@email.com">
                @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]"
                    placeholder="••••••••">
                @error('password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 flex items-center">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    class="w-4 h-4 text-[#1b1b18] bg-white dark:bg-[#0a0a0a] border-[#e3e3e0] dark:border-[#3E3E3A] rounded focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
                <label for="remember" class="ml-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Remember me
                </label>
            </div>

            <button
                type="submit"
                class="w-full bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] py-2 px-4 rounded-lg font-medium hover:opacity-90 transition">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium hover:underline">
                Register here
            </a>
        </p>
    </div>
</div>
@endsection