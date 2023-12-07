<?php

namespace App\Services\Products;

use App\Models\ProductItem;
use App\Models\ProductItemImage;
use App\DTOs\Requests\ProductItemReqDTO;
use App\DTOs\Responses\ProductItemResDTO;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductItemService
{
    private $true = "Successfull";
    private $false = "Failed";

    public function show($itemId)
    {

        // check if item exists
        $item = ProductItem::where('id', $itemId)->first();
        if (!$item) {
            return $this->false;
        } else {

            $itemImages = [];
            $images2 = array($item->product_item_images()->get('url'));
            $itemDTO = new ProductItemResDTO(
                $item->id,
                $item->product_id,
                $item->size,
                $item->color,
                $item->color_image,
                $item->qty_in_stock,
                $images2,
            );


            return $itemDTO->toArray();
        }
    }

    public function updateQtyInStock($itemId, $qtyInStock)
    {
        $item = ProductItem::where('id', $itemId)->first();
        if (!$item) {
            return $this->false;
        } else {
            $item->qty_in_stock = $qtyInStock;
            $item->save();
            return $this->true;
        }
    }

    public function add($productItemDTO)
    {

        //return $productItemDTO->toArray();
        foreach ($productItemDTO->getSize() as $size) {
            $item = new ProductItem();
            $item->product_id = $productItemDTO->getProductId();
            $item->size = $size;
            $item->color = $productItemDTO->getColor();
            $item->color_image = $productItemDTO->getColorImage();
            $item->qty_in_stock = $productItemDTO->getQtyInStock();
            $item->save();
        }

        $itemImages = $productItemDTO->getProductItemImages();
        foreach ($itemImages as $image) {
            $itemImage = new ProductItemImage();
            $itemImage->product_item_id = $item->id;
            
            $img_link = Cloudinary::upload($image->getRealPath())->getSecurePath();
            $itemImage->url = $img_link;

            $itemImage->save();
        }

        return $this->true;
    }

    public function delete($listId)
    {
        $cnt = 0;
        $listId = json_decode($listId);
        foreach ($listId as $id) {

            $images = ProductItemImage::where('product_item_id', $id)->get();
            foreach ($images as $image) {
                $image->delete();
            }


            $item = ProductItem::where('id', $id)->first();
            if (!$item) {
                continue;
            } else {
                $item->delete();
                $cnt++;
            }
        }
        return "Deleted " . $cnt . " items";
    }
}
