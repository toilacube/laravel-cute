<?php

namespace App\DTO;

class ProductItemDTO
{
    public array $size;
    public array $color;
    public int $qty;
    public array $images; // Adding images array to the DTO

    public function __construct(
        array $size,
        array $color,
        int $qty,
        array $images // Accepting images array in the constructor
    ) {
        $this->size = $size;
        $this->color = $color;
        $this->qty = $qty;
        $this->images = $images;
    }

    public function getSize(): array
    {
        return $this->size;
    }

    public function getColor(): array
    {
        return $this->color;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function getImages(): array
    {
        return $this->images;
    }
    
}
