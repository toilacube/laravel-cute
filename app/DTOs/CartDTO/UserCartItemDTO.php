<?php

namespace App\DTOs\CartDTO;

use WendellAdriel\ValidatedDTO\SimpleDTO;

class UserCartItemDTO extends SimpleDTO
{
    public int $productItemId;
    public int $productId;
    public string $name;
    public int $price;
    public string $color;
    public string $size;
    public string $img;
    public int $qty;
    public array $allItemsOfProduct;
    
    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return  [
            'productItemId' => 'int',
            'productId' => 'int',
            'price' => 'int',
            'qty' => 'int',
            'allItemsOfProduct' => 'array', // Assuming this is an array property
        ];
    }
}
