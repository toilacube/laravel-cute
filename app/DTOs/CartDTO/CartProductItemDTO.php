<?php

namespace App\DTOs\CartDTO;

class CartProductItemDTO {
    public $productItemId;
    public $productId;
    public $name;
    public $price;
    public $color;
    public $size;
    public $img;
    public $qty;
    public $allItemsOfProduct;

    public function __construct($productItemId, $productId, $name, $price, $color, $size, $img, $qty) {
        $this->productItemId = $productItemId;
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
        $this->color = $color;
        $this->size = $size;
        $this->img = $img;
        $this->qty = $qty;
        $this->allItemsOfProduct = [];
    }

    public function addProductItem($productItemId, $size, $color) {
        $this->allItemsOfProduct[] = [
            "productItemId" => $productItemId,
            "size" => $size,
            "color" => $color
        ];
    }
}