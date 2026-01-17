<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'integer'],
            'movie_title' => ['required', 'string', 'max:255'],
            'review_text' => ['required', 'string', 'min:10', 'max:5000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:10'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'movie_id.required' => 'Movie ID is required.',
            'movie_title.required' => 'Movie title is required.',
            'review_text.required' => 'Review text is required.',
            'review_text.min' => 'Review must be at least 10 characters.',
            'review_text.max' => 'Review cannot exceed 5000 characters.',
            'rating.min' => 'Rating must be between 1 and 10.',
            'rating.max' => 'Rating must be between 1 and 10.',
        ];
    }
}
