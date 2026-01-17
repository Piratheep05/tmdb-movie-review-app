<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();

        // User statistics (optimized - single query)
        $userStats = Review::where('user_id', $user->id)
            ->selectRaw('
                COUNT(*) as total,
                AVG(CASE WHEN rating IS NOT NULL THEN rating END) as avg_rating,
                COUNT(CASE WHEN rating IS NOT NULL THEN 1 END) as with_rating
            ')
            ->first();

        $userReviewCount = $userStats->total ?? 0;
        $userAverageRating = $userStats->avg_rating ? round($userStats->avg_rating, 1) : null;

        // Overall statistics (cached for 5 minutes)
        $overallStats = \Illuminate\Support\Facades\Cache::remember('dashboard_overall_stats', 300, function () {
            return [
                'total_reviews' => Review::count(),
                'total_users' => \App\Models\User::count(),
                'avg_rating' => Review::whereNotNull('rating')->avg('rating'),
            ];
        });

        $totalReviews = $overallStats['total_reviews'];
        $totalUsers = $overallStats['total_users'];
        $overallAverageRating = $overallStats['avg_rating'] ? round($overallStats['avg_rating'], 1) : null;

        // Reviews by month (last 6 months) - use database aggregation (much faster)
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();

        // Generate all 6 months to ensure complete data
        $allMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $allMonths[] = now()->subMonths($i)->format('Y-m');
        }

        // Get counts from database (only aggregates, no full records)
        $reviewsByMonthRaw = Review::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        $reviewsByMonth = [];
        foreach ($allMonths as $month) {
            $reviewsByMonth[$month] = $reviewsByMonthRaw[$month] ?? 0;
        }

        // User reviews by month - use database aggregation
        $userReviewsByMonthRaw = Review::where('user_id', $user->id)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        $userReviewsByMonth = [];
        foreach ($allMonths as $month) {
            $userReviewsByMonth[$month] = $userReviewsByMonthRaw[$month] ?? 0;
        }

        // Rating distribution (all reviews) - already optimized
        $ratingDistribution = Review::whereNotNull('rating')
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // User rating distribution - optimized
        $userRatingDistribution = Review::where('user_id', $user->id)
            ->whereNotNull('rating')
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Recent reviews (only load needed columns, limit to 5)
        $recentReviews = Review::where('user_id', $user->id)
            ->select('movie_title', 'review_text', 'rating', 'created_at')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'userReviewCount' => $userReviewCount,
            'userAverageRating' => $userAverageRating,
            'totalReviews' => $totalReviews,
            'totalUsers' => $totalUsers,
            'overallAverageRating' => $overallAverageRating,
            'reviewsByMonth' => $reviewsByMonth,
            'userReviewsByMonth' => $userReviewsByMonth,
            'ratingDistribution' => $ratingDistribution,
            'userRatingDistribution' => $userRatingDistribution,
            'recentReviews' => $recentReviews,
        ]);
    }
}
