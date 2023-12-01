<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Services\Cart\CartService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function __construct(private CartService $cartService)
    {
    }

    public function cart(Request $request)
    {


        $userId = Auth::id();
        return ($this->cartService->getUserCart($userId));
    }

    public function addToCart(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $jsonData = $request->json()->all();

            $productItemId = $jsonData['productItemId'];
            $quantity = $jsonData['quantity'];
            return $this->cartService->addToCart($userId, $productItemId, $quantity);
        } else
            return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function updateQty(Request $request)
    {
        $userId = Auth::id();

        $jsonData = $request->json()->all();

        $productItemId = $jsonData['productItemId'];
        $quantity = $jsonData['quantity'];
        return $this->cartService->updateQty($userId, $productItemId, $quantity);
    }

    public function replaceCartItem(Request $request)
    {
        $userId = Auth::id();

        $jsonData = $request->json()->all();

        $oldProductItemId = $jsonData['oldProductItemId'];
        $newProductItemId = $jsonData['newProductItemId'];
        return $this->cartService->replaceCartItem($userId, $oldProductItemId, $newProductItemId);
    }

    public function removeCartItem(Request $request)
    {

        $userId = Auth::id();

        $rawData = $request->getContent();

        $productItemId = (int)$rawData;

        return $this->cartService->removeCartItem($userId, $productItemId);
    }
}
