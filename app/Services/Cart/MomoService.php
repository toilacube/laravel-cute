<?php

namespace App\Services\Cart;

use App\Models\ShopOrder;
use App\Services\Order\OrderService;

class MomoService
{
    public function __construct(private OrderService $orderService)
    {
    }

    
}
