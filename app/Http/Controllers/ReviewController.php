<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Services\TmdbService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        private TmdbService $tmdbService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Review::with(['user', 'movie'])
            ->latest();

        // Filter by movie if provided
        if ($request->has('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        // Filter by user if provided
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $reviews = $query->paginate(15);

        return view('reviews.index', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $movieId): View
    {
        $movie = $this->tmdbService->getMovieDetails($movieId);

        if (!$movie) {
            abort(404, 'Movie not found');
        }

        // Check if user already has a review
        $existingReview = Review::where('movie_id', $movieId)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'You already have a review for this movie. You can edit it instead.');
        }

        return view('reviews.create', [
            'movie' => $movie,
            'movieId' => $movieId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request): RedirectResponse
    {
        $review = Review::create([
            'user_id' => auth()->id(),
            'movie_id' => $request->movie_id,
            'movie_title' => $request->movie_title,
            'review_text' => $request->review_text,
            'rating' => $request->rating,
        ]);

        return redirect()
            ->route('movies.show', $request->movie_id)
            ->with('success', 'Review created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $review = Review::with('user')->findOrFail($id);

        // Check authorization
        if ($review->user_id !== auth()->id()) {
            abort(403, 'You can only edit your own reviews.');
        }

        $movie = $this->tmdbService->getMovieDetails($review->movie_id);

        return view('reviews.edit', [
            'review' => $review,
            'movie' => $movie,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);

        // Check authorization
        if ($review->user_id !== auth()->id()) {
            abort(403, 'You can only update your own reviews.');
        }

        $review->update([
            'review_text' => $request->review_text,
            'rating' => $request->rating,
        ]);

        return redirect()
            ->route('movies.show', $review->movie_id)
            ->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);

        // Check authorization
        if ($review->user_id !== auth()->id()) {
            abort(403, 'You can only delete your own reviews.');
        }

        $movieId = $review->movie_id;
        $review->delete();

        return redirect()
            ->route('movies.show', $movieId)
            ->with('success', 'Review deleted successfully!');
    }
}
