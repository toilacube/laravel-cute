<?php
namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller {
    public function __construct(private OrderService $orderService)
    {
    }

    public function get(){
        $userId = Auth::id();
        return $this->orderService->get($userId);
    }

    public function getAll(){
        return $this->orderService->getAll();
    }

    public function updateStatus(Request $request){
        $orderId = $request->orderId ;
        $orderStatus = $request->status;
        return $this->orderService->updateStatus($orderId, $orderStatus);
    }
}