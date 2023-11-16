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

    public function index(Request $request)
    {

        if(Auth::check()){
            //return response()->json(["user id" => Auth::id()]);
            $userId = Auth::id();
            return ($this->cartService->getUserCart($userId));
        }
        else 
            return response()->json(['error' => 'Unauthorized'], 401);
    }
}
