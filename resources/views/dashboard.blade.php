@extends('layouts.app')

@section('title', 'Dashboard - Movie Review App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-8">
        <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
            Welcome, {{ Auth::user()->name }}! 🎬
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">My Reviews</h2>
                <p class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $userReviewCount }}
                </p>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">Total reviews written</p>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">My Avg Rating</h2>
                <p class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $userAverageRating ? $userAverageRating : 'N/A' }}
                </p>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">Out of 10</p>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Total Reviews</h2>
                <p class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $totalReviews }}
                </p>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">All users</p>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Overall Avg</h2>
                <p class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ $overallAverageRating ? $overallAverageRating : 'N/A' }}
                </p>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">Out of 10</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
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

        @if($userReviewCount > 0 || $totalReviews > 0)
        <!-- Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @if($userReviewCount > 0)
            <!-- User Reviews Over Time -->
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-4">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">My Reviews Over Time</h2>
                <div class="h-48">
                    <canvas id="userReviewsChart"></canvas>
                </div>
            </div>

            <!-- User Rating Distribution -->
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-4">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">My Rating Distribution</h2>
                <div class="h-48">
                    <canvas id="userRatingChart"></canvas>
                </div>
            </div>
            @endif

            @if($totalReviews > 0)
            <!-- All Reviews Over Time -->
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-4">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">All Reviews Over Time</h2>
                <div class="h-48">
                    <canvas id="allReviewsChart"></canvas>
                </div>
            </div>

            <!-- Overall Rating Distribution -->
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-4">
                <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Overall Rating Distribution</h2>
                <div class="h-48">
                    <canvas id="overallRatingChart"></canvas>
                </div>
            </div>
            @endif
        </div>
        @endif

        @if($userReviewCount > 0)
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Recent Reviews</h2>
            <div class="space-y-4">
                @foreach($recentReviews as $review)
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart.js configuration for dark mode
    Chart.defaults.color = '#706f6c';
    Chart.defaults.borderColor = '#e3e3e0';

    // Check if dark mode is active
    const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches ||
        document.documentElement.classList.contains('dark');

    if (isDarkMode) {
        Chart.defaults.color = '#A1A09A';
        Chart.defaults.borderColor = '#3E3E3A';
    }

    @if($userReviewCount > 0)
    // User Reviews Over Time Chart
    const userReviewsCtx = document.getElementById('userReviewsChart');
    if (userReviewsCtx) {
        const userReviewsData = @json($userReviewsByMonth);
        const months = Object.keys(userReviewsData);
        const counts = Object.values(userReviewsData);

        new Chart(userReviewsCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'My Reviews',
                    data: counts,
                    borderColor: '#1b1b18',
                    backgroundColor: 'rgba(27, 27, 24, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: true
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // User Rating Distribution Chart
    const userRatingCtx = document.getElementById('userRatingChart');
    if (userRatingCtx) {
        const userRatingData = @json($userRatingDistribution);
        const ratings = Array.from({
            length: 10
        }, (_, i) => i + 1);
        const counts = ratings.map(r => userRatingData[r] || 0);

        new Chart(userRatingCtx, {
            type: 'bar',
            data: {
                labels: ratings.map(r => r + '/10'),
                datasets: [{
                    label: 'Number of Reviews',
                    data: counts,
                    backgroundColor: '#1b1b18',
                    borderColor: '#1b1b18',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: true
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
    @endif

    @if($totalReviews > 0)
    // All Reviews Over Time Chart
    const allReviewsCtx = document.getElementById('allReviewsChart');
    if (allReviewsCtx) {
        const allReviewsData = @json($reviewsByMonth);
        const months = Object.keys(allReviewsData);
        const counts = Object.values(allReviewsData);

        new Chart(allReviewsCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'All Reviews',
                    data: counts,
                    borderColor: '#f53003',
                    backgroundColor: 'rgba(245, 48, 3, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: true
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // Overall Rating Distribution Chart
    const overallRatingCtx = document.getElementById('overallRatingChart');
    if (overallRatingCtx) {
        const overallRatingData = @json($ratingDistribution);
        const ratings = Array.from({
            length: 10
        }, (_, i) => i + 1);
        const counts = ratings.map(r => overallRatingData[r] || 0);

        new Chart(overallRatingCtx, {
            type: 'bar',
            data: {
                labels: ratings.map(r => r + '/10'),
                datasets: [{
                    label: 'Number of Reviews',
                    data: counts,
                    backgroundColor: '#f53003',
                    borderColor: '#f53003',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: true
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
    @endif

    // Update chart colors for dark mode
    if (isDarkMode) {
        document.querySelectorAll('canvas').forEach(canvas => {
            const chart = Chart.getChart(canvas);
            if (chart) {
                chart.options.scales.x.ticks.color = '#A1A09A';
                chart.options.scales.y.ticks.color = '#A1A09A';
                chart.options.scales.x.grid.color = '#3E3E3A';
                chart.options.scales.y.grid.color = '#3E3E3A';
                chart.update();
            }
        });
    }
</script>
@endpush
@endsection