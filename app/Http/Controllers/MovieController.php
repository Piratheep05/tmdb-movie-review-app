<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function __construct(
        private TmdbService $tmdbService
    ) {}

    /**
     * Show the movie search page.
     */
    public function search(Request $request): View
    {
        $query = $request->get('q', '');
        $page = (int) $request->get('page', 1);

        $results = [
            'results' => [],
            'total_results' => 0,
            'page' => 1,
            'total_pages' => 1,
        ];

        if ($query) {
            $results = $this->tmdbService->searchMovies($query, $page);

            // Add poster URLs to results
            foreach ($results['results'] as &$movie) {
                $movie['poster_url'] = $this->tmdbService->getPosterUrl($movie['poster_path'] ?? null);
            }
        }

        return view('movies.search', [
            'movies' => $results['results'] ?? [],
            'query' => $query,
            'currentPage' => $results['page'] ?? 1,
            'totalPages' => $results['total_pages'] ?? 1,
            'totalResults' => $results['total_results'] ?? 0,
        ]);
    }
}
