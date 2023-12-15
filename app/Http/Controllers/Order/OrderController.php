<?php
namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\DTOs\Requests\AddOrderDTO;
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
        return 1;
        //return $this->orderService->getAll();
    }

    public function updateStatus(Request $request){
        $orderId = $request->orderId ;
        $orderStatus = $request->status;
        return $this->orderService->updateStatus($orderId, $orderStatus);
    }

    public function create(Request $request){
        $userId = Auth::id();
        $AddOrderDTO = new AddOrderDTO(
            $request->shippingAddress,
            $request->name,
            $request->phone,
            $request->email,
            $request->paymentMethod,
            $request->shippingMethod,
        );
        return $this->orderService->create($userId, $AddOrderDTO);
    }

}