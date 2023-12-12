<?php

namespace App\Http\Controllers\Cart;


use Exception;
use App\Models\ShopOrder;
use Illuminate\Http\Request;
use App\Services\Cart\VnPayService;

class VnPayController
{

    public function __construct(private VnPayService $vnPayService)
    {
    }
    public function createPayment(Request $request)
    {
        $currentBillId = ShopOrder::max('id');
        $billId = ($currentBillId + 1).':'.time();


        
    }

    public function confirmPayment(Request $request)
    {
        $inputData = [];
        $returnData = [];

        // Retrieve all GET parameters
        foreach ($request->query() as $key => $value) {
            if (substr($key, 0, 4) === "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $returnData = $this->vnPayService->confirmPayment($inputData);

        // Return response as JSON
        return response($returnData);
    }
}
