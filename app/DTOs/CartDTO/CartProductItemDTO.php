<?php

namespace App\DTOs\CartDTO;

use WendellAdriel\ValidatedDTO\SimpleDTO;

class CartProductItemDTO extends SimpleDTO
{
    public int $productItemId;
    public string $size;
    public string $color;



    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
       
        ];
    }
}
