<?php
namespace App\DTOs\Responses;
class UserReviewDTO
{
    public int $id;
    public string $userId;
    public ?string $name;
    public int $orderedProductId;
    public string $color;
    public string $size;
    public int $ratingValue;
    public string $comment;
    public string $createdDate;

    public function __construct(
        int $id,
        string $userId,
        ?string $name,
        int $orderedProductId,
        string $color,
        string $size,
        int $ratingValue,
        string $comment,
        string $createdDate
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->orderedProductId = $orderedProductId;
        $this->color = $color;
        $this->size = $size;
        $this->ratingValue = $ratingValue;
        $this->comment = $comment;
        $this->createdDate = $createdDate;
    }

    // write toArray function
    public function toArray()
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'name' => $this->name,
            'orderedProductId' => $this->orderedProductId,
            'color' => $this->color,
            'size' => $this->size,
            'ratingValue' => $this->ratingValue,
            'comment' => $this->comment,
            'createdDate' => $this->createdDate,
        ];
    }
}
