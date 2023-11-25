<?php

namespace App\Services\Cart;

use App\Models\User;
use App\Models\ProductItem;
use App\Models\ShoppingCart;
use App\Models\ProductItemImage;
use App\Models\ShoppingCartItem;
use App\DTOs\CartDTO\UserCartItemDTO;
use App\DTOs\CartDTO\CartProductItemDTO;

/**
 * Class CartService.   
 */
class CartService

{
    private $true = "Successfull";
    private $false = "Failed";

    public function getUserCart($userId)
    { // userId can be get from access-token

        $cartId = ShoppingCart::where('user_id', $userId)->value('id');

        $cartItems = ShoppingCartItem::where("cart_id", $cartId)->with('product_item.product.product_items')->get();

        $itemDTOs = [];
        foreach ($cartItems as $cartItem) {
            $img = ProductItemImage::where("product_item_id", $cartItem->product_item_id)->value('url');

            $itemDTO = new CartProductItemDTO(
                $cartItem->product_item_id,
                $cartItem->product_item->product->id,
                $cartItem->product_item->product->name,
                $cartItem->product_item->product->price_int,
                $cartItem->product_item->color,
                $cartItem->product_item->size,
                $img,
                $cartItem->qty
            );

            foreach ($cartItem->product_item->product->product_items as $productItem) {
                $itemDTO->addProductItem(
                    $productItem->id,
                    $productItem->size,
                    $productItem->color
                );
            }

            $itemDTOs[] = $itemDTO;
        }
        return response($itemDTOs);
    }

    public function addToCart($userId, $productItemId, $quantity)
    {
        // Find the shopping cart associated with the user
        $shoppingCart = ShoppingCart::where('user_id', $userId)->first();

        // If no cart exists for the user, you might want to handle this scenario accordingly
        if (!$shoppingCart) {
            // Handle no shopping cart found for the user
            // For example, create a new shopping cart for the user
            $shoppingCart = ShoppingCart::create([
                'user_id' => $userId,
                // Other cart properties if available
            ]);
        }

        // Find the product item based on the provided ID
        $productItem = ProductItem::find($productItemId);

        if ($productItem) {
            // Check if the item already exists in the cart
            $existingCartItem = ShoppingCartItem::where('cart_id', $shoppingCart->id)
                ->where('product_item_id', $productItemId)
                ->first();

            if ($existingCartItem) {
                // Update quantity if the item already exists in the cart
                $existingCartItem->qty += $quantity;
                $existingCartItem->save();
            } else {
                // Add a new item to the cart if it doesn't exist
                ShoppingCartItem::create([
                    'cart_id' => $shoppingCart->id,
                    'product_item_id' => $productItemId,
                    'qty' => $quantity,
                    // Other cart item properties if available
                ]);
            }

            // You can return the added/updated cart item if needed
            // $addedCartItem = ShoppingCartItem::where('cart_id', $shoppingCart->id)
            //     ->where('product_item_id', $productItemId)
            //     ->first();

            return response("successfully"); // Or return the addedCartItem or any other response
        } else {
            // Handle case when the product item doesn't exist
            return "FAILED!!!!!!!"; // Or throw an exception or return an error response
        }
    }

    public function updateQty($userId, $productItemId, $newQuantity)
    {

        $shoppingCart = ShoppingCart::where('user_id', $userId)->first();

        $cartItem = ShoppingCartItem::where('cart_id', $shoppingCart->id)
            ->where('product_item_id', $productItemId)
            ->first();

        if ($cartItem) {
            $cartItem->qty = $newQuantity;
            $cartItem->save();
            return "true";
        }

        return "false";
    }

    public function replaceCartItem($userId, $oldProductItemId, $newProductItemId)
    {
        $shoppingCart = ShoppingCart::where('user_id', $userId)->first();

        $oldCartItem = ShoppingCartItem::where('cart_id', $shoppingCart->id)
            ->where('product_item_id', $oldProductItemId)
            ->first();

        $newCartItem = ShoppingCartItem::where('cart_id', $shoppingCart->id)
            ->where('product_item_id', $newProductItemId)
            ->first();

        if ($oldCartItem) {
            if ($newCartItem) {
                // If both old and new items exist, update the quantity of the new item
                $newCartItem->qty += $oldCartItem->qty;
                $newCartItem->save();
            } else {
                // If the new item doesn't exist, update the product_item_id of the old item
                $oldCartItem->product_item_id = $newProductItemId;
                $oldCartItem->save();
            }

            // Delete the old cart item since its data has been merged
            $oldCartItem->delete();

            return $this->true;
        }

        return $this->false;
    }


    public function removeCartItem($userId, $productItemId)
    {

        $shoppingCart = ShoppingCart::where('user_id', $userId)->first();
        $cartItem = ShoppingCartItem::where('cart_id', $shoppingCart->id)
            ->where('product_item_id', $productItemId)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return $this->true;
        }
        return  $this->false;
    }
}
