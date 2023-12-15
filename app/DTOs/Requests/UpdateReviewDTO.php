<?php
namespace App\DTOs\Requests;

class UpdateReviewDTO {
    public int $id;
    public int $ratingValue;
    public string $comment;

    public function __construct(int $id, int $ratingValue, string $comment)
    {
        $this->id = $id;
        $this->ratingValue = $ratingValue;
        $this->comment = $comment;
    }
    public function toArray()
    {
        return [
            'id' => $this->id,
            'ratingValue' => $this->ratingValue,
            'comment' => $this->comment,
        ];
    }
}