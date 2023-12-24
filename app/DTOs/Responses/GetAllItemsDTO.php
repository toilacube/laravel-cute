<?php
namespace App\DTOs\Responses;
class GetAllItemsDTO
{
    public $id;
    public $productId;
    public $size;
    public $color;
    public $colorImage;
    public $qtyInStock;
    public $name;
    public $priceStr;

    public function __construct($id, $productId, $size, $color, $colorImage, $qtyInStock, $name, $priceStr)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->size = $size;
        $this->color = $color;
        $this->colorImage = $colorImage;
        $this->qtyInStock = $qtyInStock;
        $this->name = $name;
        $this->priceStr = $priceStr;
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
            'name' => $this->name,
            'priceStr' => $this->priceStr,
        ];
    }
}
