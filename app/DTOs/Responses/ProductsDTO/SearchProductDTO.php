<?php

namespace App\DTOs\Responses\ProductsDTO;

class SearchProductDTO
{
    public int $id;
    public int $categoryId;
    public string $name;
    public string $description;
    public int $priceInt;
    public string $priceStr;
    public ?string $category;
    public ?string $img;

    public function __construct(
        int $id,
        int $categoryId,
        string $name,
        string $description,
        int $priceInt,
        string $priceStr,
        ?string $category = null,
        ?string $img = null
    ) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->priceInt = $priceInt;
        $this->priceStr = $priceStr;
        $this->category = $category;
        $this->img = $img;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['categoryId'],
            $data['name'],
            $data['description'],
            $data['priceInt'],
            $data['priceStr'],
            $data['category'] ?? null,
            $data['img'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'name' => $this->name,
            'description' => $this->description,
            'priceInt' => $this->priceInt,
            'priceStr' => $this->priceStr,
            'category' => $this->category,
            'img' => $this->img,
        ];
    }
}
