<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : $this->resource->toArray();

        return [
            'id' => $data['id'] ?? null,
            'title' => $data['title'] ?? null,
            'overview' => $data['overview'] ?? null,
            'release_date' => $data['release_date'] ?? null,
            'poster_path' => $data['poster_path'] ?? null,
            'poster_url' => $data['poster_url'] ?? null,
            'backdrop_path' => $data['backdrop_path'] ?? null,
            'backdrop_url' => $data['backdrop_url'] ?? null,
            'vote_average' => $data['vote_average'] ?? null,
            'vote_count' => $data['vote_count'] ?? null,
            'runtime' => $data['runtime'] ?? null,
            'genres' => $data['genres'] ?? [],
            'credits' => $data['credits'] ?? null,
        ];
    }
}
