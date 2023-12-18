<?php

namespace App\Services\Cart;

use Exception;
use App\Models\ShopOrder;
use App\Models\ShoppingCart;
use App\Services\Order\OrderService;

class VnPayService
{
    public function __construct(private OrderService $orderService)
    {
    }

}
