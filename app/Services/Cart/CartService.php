<?php

namespace App\Services\Cart;

use App\DTOs\CartDTO\CartProductItemDTO;
use App\DTOs\CartDTO\UserCartItemDTO;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\Models\User;

/**
 * Class CartService.   
 */
class CartService
{
    public function getUserCart($userId)
    { // userId can be get from access-token

        $cartId = ShoppingCart::where('user_id', $userId)->get('id');
        $cartItems = ShoppingCartItem::where("cart_id", $cartId[0]->id)->with('product_item.product.product_items')->get();
        $array = [];
        foreach ($cartItems as $cartItem) {
            $item = array([
                "productItemId" => $cartItem["product_item_id"],
                "productId" => $cartItem["product_item"]["product"]["id"],
                "name" => $cartItem["product_item"]["product"]["name"],
                "price" => $cartItem["product_item"]["product"]["price_int"],
                "color" => $cartItem["product_item"]["color"],
                "size" => $cartItem["product_item"]["size"],
                //"img" => $$cartItem["product_item"]["product"]["product_items"],
                 "qty" => $cartItem["qty"],
                 'allItemsOfProduct' => []
            ]);
            // foreach ($cartItem["product_item"]["product"]["product_items"] as $productItems) {
            //     $productItem =  array([
            //         "productItemId" => $productItems["id"],
            //         "size" => $productItems["size"],
            //         "color" => $productItems["color"]
            //     ]);
            //     array_merge($item['allItemsOfProduct'], $productItem);
            // }

            array_push($array,  $item);
        }
        return response($array);
    }
}
