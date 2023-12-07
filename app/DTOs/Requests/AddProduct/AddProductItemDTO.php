<?php

namespace App\DTOs\Requests\AddProduct;

class AddProductItemDTO
{
    public array $size;
    public string $url;
    public string $color;
    public int $qty;
    public array $images; // Adding images array to the DTO

    public function __construct(
        // array $size,
        // array $color,
        // int $qty,
        // array $images // Accepting images array in the constructor
    ) {
        // $this->size = $size;
        // $this->color = $color;
        // $this->qty = $qty;
        // $this->images = $images;
    }

    public function getSize(): array
    {
        return $this->size;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getColor(): string
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

    public function setSize(array $size): void
    {
        $this->size = $size;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function setQty(int $qty): void
    {
        $this->qty = $qty;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }
}

  
