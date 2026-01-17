<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    private string $apiKey;

    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.api_url');
    }

    /**
     * Search for movies by query.
     *
     * @return array<string, mixed>
     */
    public function searchMovies(string $query, int $page = 1): array
    {
        $cacheKey = "tmdb_search_{$query}_page_{$page}";

        return Cache::remember($cacheKey, 3600, function () use ($query, $page) {
            try {
                $response = Http::timeout(10)
                    ->get("{$this->baseUrl}/search/movie", [
                        'api_key' => $this->apiKey,
                        'query' => $query,
                        'page' => $page,
                        'language' => 'en-US',
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDB API search failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'results' => [],
                    'total_results' => 0,
                    'page' => 1,
                    'total_pages' => 1,
                ];
            } catch (RequestException $e) {
                Log::error('TMDB API request exception', [
                    'message' => $e->getMessage(),
                ]);

                return [
                    'results' => [],
                    'total_results' => 0,
                    'page' => 1,
                    'total_pages' => 1,
                ];
            }
        });
    }

    /**
     * Get movie details by ID.
     *
     * @return array<string, mixed>|null
     */
    public function getMovieDetails(int $movieId): ?array
    {
        $cacheKey = "tmdb_movie_{$movieId}";

        return Cache::remember($cacheKey, 7200, function () use ($movieId) {
            try {
                $response = Http::timeout(10)
                    ->get("{$this->baseUrl}/movie/{$movieId}", [
                        'api_key' => $this->apiKey,
                        'language' => 'en-US',
                        'append_to_response' => 'credits,videos,images',
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDB API movie details failed', [
                    'movie_id' => $movieId,
                    'status' => $response->status(),
                ]);

                return null;
            } catch (RequestException $e) {
                Log::error('TMDB API request exception', [
                    'movie_id' => $movieId,
                    'message' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }

    /**
     * Get movie poster image URL.
     */
    public function getPosterUrl(?string $posterPath, string $size = 'w500'): ?string
    {
        if (! $posterPath) {
            return null;
        }

        return "https://image.tmdb.org/t/p/{$size}{$posterPath}";
    }

    /**
     * Get movie backdrop image URL.
     */
    public function getBackdropUrl(?string $backdropPath, string $size = 'w1280'): ?string
    {
        if (! $backdropPath) {
            return null;
        }

        return "https://image.tmdb.org/t/p/{$size}{$backdropPath}";
    }

    /**
     * Get popular movies.
     *
     * @return array<string, mixed>
     */
    public function getPopularMovies(int $page = 1): array
    {
        $cacheKey = "tmdb_popular_page_{$page}";

        return Cache::remember($cacheKey, 3600, function () use ($page) {
            try {
                $response = Http::timeout(10)
                    ->get("{$this->baseUrl}/movie/popular", [
                        'api_key' => $this->apiKey,
                        'page' => $page,
                        'language' => 'en-US',
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return [
                    'results' => [],
                    'total_results' => 0,
                    'page' => 1,
                    'total_pages' => 1,
                ];
            } catch (RequestException $e) {
                Log::error('TMDB API popular movies failed', [
                    'message' => $e->getMessage(),
                ]);

                return [
                    'results' => [],
                    'total_results' => 0,
                    'page' => 1,
                    'total_pages' => 1,
                ];
            }
        });
    }
}
