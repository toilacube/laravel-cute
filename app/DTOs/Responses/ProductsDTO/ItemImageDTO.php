<?php

namespace App\DTOs\Responses\ProductsDTO;

class ItemImageDTO
{
    private int $id;
    private int $productItemId;
    private string $url;
    private ?array $productItem;

    public function __construct(
        int $id,
        int $productItemId,
        string $url,
        ?array $productItem = null
    ) {
        $this->id = $id;
        $this->productItemId = $productItemId;
        $this->url = $url;
        $this->productItem = $productItem;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['productItemId'],
            $data['url'],
            $data['productItem'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productItemId' => $this->productItemId,
            'url' => $this->url,
            'productItem' => $this->productItem,
        ];
    }

    // Getter and setter methods for each property can be added similarly to previous DTOs.
}
