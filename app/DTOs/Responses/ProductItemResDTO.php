<?php

namespace App\DTOs\Responses;

class ProductItemResDTO
{
    public int $id;
    public int $productId;
    public string $size;
    public string $color;
    public string $colorImage;
    public int $qtyInStock;
    public array $productItemImages;

    public function __construct(
        int $id,
        int $productId,
        string $size,
        string $color,
        string $colorImage,
        int $qtyInStock,
        array $productItemImages
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->size = $size;
        $this->color = $color;
        $this->colorImage = $colorImage;
        $this->qtyInStock = $qtyInStock;
        $this->productItemImages = $productItemImages;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getSize(): string
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

    public function toArray(): array
    {
        $imagesArray = [];
        foreach ($this->productItemImages as $image) {
            $imagesArray[] = $image->toArray();
        }

        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'size' => $this->size,
            'color' => $this->color,
            'colorImage' => $this->colorImage,
            'qtyInStock' => $this->qtyInStock,
            'productItemImages' => $imagesArray,
        ];
    }
}
