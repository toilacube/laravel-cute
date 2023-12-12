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

    public function createPayment($orderId, $amount)
    {

        $billId = $orderId . ':' . time();

        $vnp_TmnCode = env('VNPAY_TMNCODE'); //Mã website tại VNPAY 
        $vnp_HashSecret = env('VNPAY_HASHSECRET'); //Chuỗi bí mật
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8000/api/cart/checkout/return";

        $vnp_TxnRef = $orderId; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY

        $vnp_OrderInfo = "Thanh toan don hang";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr =  $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnpSecureHash = hash_hmac('sha512', $hashdata,  $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return response()->json(["payUrl" => $vnp_Url]);
    }


    public function confirmPayment($inputData)
    {
        // Check if vnp_SecureHash exists in the input data
        if (isset($inputData['vnp_SecureHash'])) {
            $vnpSecureHash = $inputData['vnp_SecureHash'];
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);

            $hashData = "";
            foreach ($inputData as $key => $value) {
                $hashData .= ($hashData ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            }

            $vnpHashSecret = env('VNPAY_HASHSECRET'); // Replace with your VNPAY hash secret
            $secureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

            // Extract necessary data from input
            $vnpTranId = $inputData['vnp_TransactionNo'];
            $vnpBankCode = $inputData['vnp_BankCode'];
            $vnpAmount = $inputData['vnp_Amount'] / 100; // Convert amount to appropriate format

            // Initial status
            $status = 0;
            $orderId = $inputData['vnp_TxnRef'];

            try {
                // Check the validity of the signature
                if ($secureHash === $vnpSecureHash) {
                    // Retrieve order information from your database
                    $order = NULL; // Replace with logic to fetch order data by $orderId from your database
                    $order = ShopOrder::where('id', $orderId)->first();

                    if ($order !== NULL) {
                        if ($order["order_total"] == $vnpAmount) {
                            if ($order["order_status"] !== NULL && $order["order_status"] == 0) {
                                if ($inputData['vnp_ResponseCode'] === '00' && $inputData['vnp_TransactionStatus'] === '00') {
                                    $status = 1; // Payment success
                                } else {
                                    $status = 2; // Payment failed or error
                                }

                                // Update order status in the database
                                // ... Your code to update order status ...
                                if($status == 1){
                                    $order = ShopOrder::where('id', $orderId)->first();
                                    $order->order_status = 1;
                                    $order->save();

                                    $this->orderService->deleteCart(ShoppingCart::where('user_id', $order->user_id)->first()['id']);
                                }
                                

                                $returnData['RspCode'] = '00';
                                $returnData['Message'] = 'Confirm Success';
                            } else {
                                $returnData['RspCode'] = '02';
                                $returnData['Message'] = 'Order already confirmed';
                            }
                        } else {
                            $returnData['RspCode'] = '04';
                            $returnData['Message'] = 'Invalid amount';
                        }
                    } else {
                        $returnData['RspCode'] = '01';
                        $returnData['Message'] = 'Order not found';
                    }
                } else {
                    $returnData['RspCode'] = '97';
                    $returnData['Message'] = 'Invalid signature';
                }
            } catch (Exception $e) {
                $returnData['RspCode'] = '99';
                $returnData['Message'] = 'Unknown error';
            }
        } else {
            $returnData['RspCode'] = '98';
            $returnData['Message'] = 'Missing vnp_SecureHash';
        }

        // Return response as JSON
        return response()->json($returnData);
    }
}
