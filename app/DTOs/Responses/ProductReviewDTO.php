<?php

namespace App\DTOs\Responses;

use App\DTOs\Responses\UserReviewDTO;

class ProductReviewDTO
{
    public int $total;
    public float $rating;
    public array $reviews;

    public function __construct(int $total, float $rating, array $reviews)
    {
        $this->total = $total;
        $this->rating = $rating;
        $this->reviews = $reviews;
    }

    public function toArray()
    {
        return [
            'total' => $this->total,
            'rating' => $this->rating,
            'reviews' => $this->reviews,
        ];
    }
}
