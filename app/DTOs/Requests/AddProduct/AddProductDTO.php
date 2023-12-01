<?php

namespace App\DTOs\Requests\AddProduct;

class AddProductDTO
{
    public int $categoryId;
    public string $name;
    public string $description;
    public int $priceInt;
    public string $priceStr;
    public array $productItems;

    public function __construct(
        int $categoryId,
        string $name,
        string $description,
        int $priceInt,
        string $priceStr,
        array $productItems
    ) {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->priceInt = $priceInt;
        $this->priceStr = $priceStr;
        $this->productItems = $productItems;
    }

    public function toArray(): array
    {
        return [
            'categoryId' => $this->categoryId,
            'name' => $this->name,
            'description' => $this->description,
            'priceInt' => $this->priceInt,
            'priceStr' => $this->priceStr,
            'productItems' => $this->productItems,
        ];
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPriceInt(): int
    {
        return $this->priceInt;
    }

    public function getPriceStr(): string
    {
        return $this->priceStr;
    }

    public function getProductItems(): array
    {
        return $this->productItems;
    }
}
