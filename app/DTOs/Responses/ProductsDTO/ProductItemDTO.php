<?php

namespace App\DTOs\Responses\ProductsDTO;

class ProductItemDTO
{
    private int $id;
    private int $productId;
    private string $size;
    private array $sizes;
    private array $itemIds;
    private string $color;
    private string $colorImage;
    private int $qtyInStock;
    private ?string $orderLines;
    private ?string $product;
    private ?array $productItemImages;

    public function __construct(
        int $id,
        int $productId,
        string $size,
        array $sizes,
        array $itemIds,
        string $color,
        string $colorImage,
        int $qtyInStock,
        ?string $orderLines = null,
        ?string $product = null,
        ?array $productItemImages = null
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->size = $size;
        $this->sizes = $sizes;
        $this->itemIds = $itemIds;
        $this->color = $color;
        $this->colorImage = $colorImage;
        $this->qtyInStock = $qtyInStock;
        $this->orderLines = $orderLines;
        $this->product = $product;
        $this->productItemImages = $productItemImages;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['productId'],
            $data['size'],
            $data['sizes'],
            $data['itemIds'],
            $data['color'],
            $data['colorImage'],
            $data['qtyInStock'],
            $data['orderLines'] ?? null,
            $data['product'] ?? null,
            $data['productItemImages'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'size' => $this->size,
            'sizes' => $this->sizes,
            'itemIds' => $this->itemIds,
            'color' => $this->color,
            'colorImage' => $this->colorImage,
            'qtyInStock' => $this->qtyInStock,
            'orderLines' => $this->orderLines,
            'product' => $this->product,
            'productItemImages' => $this->productItemImages,
        ];
    }

    // Getter and setter methods for each property can be added similarly to the previous DTO (ProductDTO).
}
