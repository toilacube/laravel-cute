<?php

namespace App\Http\Controllers\Cart;

use App\Models\ShopOrder;
use Illuminate\Http\Request;
use App\Services\Cart\MomoService;

class MomoController
{
    public function __construct(private MomoService $momoService)
    {
    }

    public function createPayment(Request $request)
    {

    }

    public function confirmPayment(Request $request)
    {
        $orderId = $request->orderId;
        $order = ShopOrder::where('id', $orderId)->first();
        $order->order_status = "paid";
        $order->save();
        return response()->json([
            'message' => 'success',
            'data' => $order
        ], 200);
    }
}
