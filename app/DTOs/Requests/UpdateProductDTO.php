<?php

namespace App\DTOs\Requests;

class UpdateProductDTO
{
    public int $id;
    public int $categoryId;
    public string $name;
    public string $description;
    public int $priceInt;
    public string $priceStr;

    public function __construct(int $id, int $categoryId, string $name, string $description, int $priceInt, string $priceStr)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->priceInt = $priceInt;
        $this->priceStr = $priceStr;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'name' => $this->name,
            'description' => $this->description,
            'priceInt' => $this->priceInt,
            'priceStr' => $this->priceStr,
        ];
    }
}
