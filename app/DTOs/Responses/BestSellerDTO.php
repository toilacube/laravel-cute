<?php

namespace App\DTOs\Responses;

class BestSellerDTO
{
    public int $productItemId;
    public int $quantity;
    public string $name;
    public string $image;
    public string $price;

    public function __construct($productItemId, $quantity, $name, $image, $price)
    {
        $this->productItemId = $productItemId;
        $this->quantity = $quantity;
        $this->name = $name;
        $this->image = $image;
        $this->price = $price;
    }

    public function toArray()
    {
        return [
            'productItemId' => $this->productItemId,
            'quantity' => $this->quantity,
            'name' => $this->name,
            'image' => $this->image,
            'price' => $this->price,
        ];
    }
}

