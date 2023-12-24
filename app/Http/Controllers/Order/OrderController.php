<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\DTOs\Requests\AddOrderDTO;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    ) {
    }

    public function get()
    {
        $userId = Auth::id();

        return $this->orderService->get($userId);
    }

    public function getAll()
    {


        return $this->orderService->getAll();
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->orderId;
        $orderStatus = $request->status;
        return $this->orderService->updateStatus($orderId, $orderStatus);
    }
    public function cancellOrder(Request $request)
    {
        $orderId = $request->orderId;
        $orderStatus = $request->status;
        return $this->orderService->cancellOrder($orderId, $orderStatus);
    }

    public function create(Request $request)
    {
        // return "ok concurrent order ok";
        $userId = Auth::id();
        $user = Auth::user();
        //return $user;
        $AddOrderDTO = new AddOrderDTO(
            $request->shippingAddress,
            $user->name,
            $user->phone_number,
            $user->email,
            $request->paymentMethod,
            $request->shippingMethod,
        );
        return $this->orderService->create($userId, $AddOrderDTO);
    }

    public function vnpayConfirmPayment(Request $request)
    {   
        return $this->orderService->vnpayConfirmPayment($request->all());
    }
}
