<?php

namespace App\DTOs\Requests;

class ProductItemReqDTO
{
    public int $productId;
    public array $size;
    public string $color;
    public string $colorImage;
    public int $qtyInStock;
    public array $productItemImages;

    public function __construct(
        int $productId,
        array $size,
        string $color,
        string $colorImage,
        int $qtyInStock,
        array $productItemImages
    ) {
        $this->productId = $productId;
        $this->size = $size;
        $this->color = $color;
        $this->colorImage = $colorImage;
        $this->qtyInStock = $qtyInStock;
        $this->productItemImages = $productItemImages;
    }

    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'size' => $this->size,
            'color' => $this->color,
            'colorImage' => $this->colorImage,
            'qtyInStock' => $this->qtyInStock,
            'productItemImages' => $this->productItemImages,
        ];
    }

    

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getSize(): array
    {
        return $this->size;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getColorImage(): string
    {
        return $this->colorImage;
    }

    public function getQtyInStock(): int
    {
        return $this->qtyInStock;
    }

    public function getProductItemImages(): array
    {
        return $this->productItemImages;
    }
}
