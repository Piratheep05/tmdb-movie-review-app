<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Services\TmdbService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    public function __construct(
        private TmdbService $tmdbService
    ) {}

    /**
     * Search movies via API.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $page = (int) $request->get('page', 1);

        if (! $query) {
            return response()->json([
                'message' => 'Query parameter "q" is required.',
            ], 400);
        }

        $results = $this->tmdbService->searchMovies($query, $page);

        // Add poster URLs to results
        foreach ($results['results'] as &$movie) {
            $movie['poster_url'] = $this->tmdbService->getPosterUrl($movie['poster_path'] ?? null);
        }

        return response()->json([
            'results' => MovieResource::collection($results['results']),
            'page' => $results['page'] ?? 1,
            'total_pages' => $results['total_pages'] ?? 1,
            'total_results' => $results['total_results'] ?? 0,
        ]);
    }

    /**
     * Get movie details via API.
     */
    public function show(int $movieId): JsonResponse
    {
        $movie = $this->tmdbService->getMovieDetails($movieId);

        if (! $movie) {
            return response()->json([
                'message' => 'Movie not found.',
            ], 404);
        }

        // Add image URLs
        $movie['poster_url'] = $this->tmdbService->getPosterUrl($movie['poster_path'] ?? null);
        $movie['backdrop_url'] = $this->tmdbService->getBackdropUrl($movie['backdrop_path'] ?? null);

        // Get reviews for this movie
        $reviews = Review::where('movie_id', $movieId)
            ->with('user')
            ->latest()
            ->get();

        // Calculate average rating
        $averageRating = Review::where('movie_id', $movieId)
            ->whereNotNull('rating')
            ->avg('rating');

        return response()->json([
            'movie' => new MovieResource($movie),
            'reviews' => ReviewResource::collection($reviews),
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $reviews->count(),
        ]);
    }

    /**
     * Get reviews for a specific movie via API.
     */
    public function reviews(int $movieId, Request $request): JsonResponse
    {
        $movie = $this->tmdbService->getMovieDetails($movieId);

        if (! $movie) {
            return response()->json([
                'message' => 'Movie not found.',
            ], 404);
        }

        $reviews = Review::where('movie_id', $movieId)
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json([
            'movie_id' => $movieId,
            'movie_title' => $movie['title'] ?? 'Unknown',
            'reviews' => ReviewResource::collection($reviews),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }
}
