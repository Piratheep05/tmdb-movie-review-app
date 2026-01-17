<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with('user')->latest();

        // Filter by movie if provided
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $reviews = $query->paginate(15);

        return ReviewResource::collection($reviews)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request): JsonResponse
    {
        $review = Review::create([
            'user_id' => $request->user()->id,
            'movie_id' => $request->movie_id,
            'movie_title' => $request->movie_title,
            'review_text' => $request->review_text,
            'rating' => $request->rating,
        ]);

        $review->load('user');

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $review = Review::with('user')->findOrFail($id);

        return (new ReviewResource($review))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, int $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        // Check authorization
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You can only update your own reviews.',
            ], 403);
        }

        $review->update([
            'review_text' => $request->review_text,
            'rating' => $request->rating,
        ]);

        $review->load('user');

        return (new ReviewResource($review))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        // Check authorization
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You can only delete your own reviews.',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully.',
        ], 200);
    }
}
