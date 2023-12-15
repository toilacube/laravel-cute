<?php
namespace App\DTOs\Requests;

class AddReviewDTO {
    public int $productItemId;
    public int $ratingValue;
    public string $comment;

    public function __construct(int $productItemId, int $ratingValue, string $comment)
    {
        $this->productItemId = $productItemId;
        $this->ratingValue = $ratingValue;
        $this->comment = $comment;
    }
    public function toArray()
    {
        return [
            'productItemId' => $this->productItemId,
            'ratingValue' => $this->ratingValue,
            'comment' => $this->comment,
        ];
    }
}