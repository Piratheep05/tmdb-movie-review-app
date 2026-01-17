<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieDetailsController extends Controller
{
    public function __construct(
        private TmdbService $tmdbService
    ) {}

    /**
     * Show movie details page.
     */
    public function show(int $movieId, Request $request): View
    {
        try {
            $movie = $this->tmdbService->getMovieDetails($movieId);

            if (! $movie) {
                abort(404, 'Movie not found');
            }
        } catch (\Exception $e) {
            \Log::error('Movie details error', [
                'movie_id' => $movieId,
                'error' => $e->getMessage(),
            ]);
            abort(500, 'Unable to load movie details. Please try again later.');
        }

        // Add image URLs
        $movie['poster_url'] = $this->tmdbService->getPosterUrl($movie['poster_path'] ?? null);
        $movie['backdrop_url'] = $this->tmdbService->getBackdropUrl($movie['backdrop_path'] ?? null);

        // Get reviews for this movie with eager loading
        $reviews = Review::where('movie_id', $movieId)
            ->with('user')
            ->latest()
            ->paginate(10);

        // Calculate average rating
        $averageRating = Review::where('movie_id', $movieId)
            ->whereNotNull('rating')
            ->avg('rating');

        // Check if current user has reviewed this movie
        $userReview = null;
        if (auth()->check()) {
            $userReview = Review::where('movie_id', $movieId)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('movies.details', [
            'movie' => $movie,
            'reviews' => $reviews,
            'averageRating' => round($averageRating, 1),
            'userReview' => $userReview,
        ]);
    }
}
