<?php
namespace App\DTOs\Responses\ProductsDTO;

class ProductItemShorterDTO {
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
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'size' => $this->size,
            'color' => $this->color,
            'colorImage' => $this->colorImage,
            'qtyInStock' => $this->qtyInStock,
            'productItemImages' => $this->productItemImages,
        ];
    }
}
    