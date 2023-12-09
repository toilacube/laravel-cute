<?php
namespace App\DTOs\Responses;

class OrderLineDTO
{
    public int $id;
    public int $productItemId;
    public string $productName;
    public string $size;
    public string $color;
    public int $quantity;
    public float $price;
    public string $img;

    public function __construct(
        int $id,
        int $productItemId,
        string $productName,
        string $size,
        string $color,
        int $quantity,
        float $price,
        string $img
    ) {
        $this->id = $id;
        $this->productItemId = $productItemId;
        $this->productName = $productName;
        $this->size = $size;
        $this->color = $color;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->img = $img;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productItemId' => $this->productItemId,
            'productName' => $this->productName,
            'size' => $this->size,
            'color' => $this->color,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'img' => $this->img,
        ];
    }
}