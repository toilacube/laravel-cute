<?php

namespace App\Services\Order;

use Exception;
use App\Models\OrderLine;
use App\Models\ShopOrder;
use App\Models\ProductItem;
use App\Models\ShoppingCart;
use App\DTOs\Responses\OrderDTO;
use App\Models\ShoppingCartItem;
use App\Services\Cart\MomoService;
use Illuminate\Support\Facades\DB;
use App\Services\Cart\VnPayService;
use App\DTOs\Responses\OrderLineDTO;
use Illuminate\Support\Facades\Redis;

class OrderService
{
    private $true = "ok";
    private $false = "error";

    public function __construct()
    {
    }

    public function get($userId)
    {
        $results = ShopOrder::where('user_id', $userId)->with('order_lines')->orderByDesc('id')->get();
        //return  $result;  

        $all = [];

        foreach ($results as $result) {
            $orderLines = [];
            foreach ($result['order_lines'] as $orderLine) {

                $product = ProductItem::where('id', $orderLine['product_item_id'])->with('product')->first()['product'];
                //return $product;
                $itemImage = ProductItem::where('id', $orderLine['product_item_id'])->with('product_item_images')->first()['product_item_images'][0]['url'];
                //return $itemImage;
                $item = ProductItem::where('id', $orderLine['product_item_id'])->first();

                $orderLineDTO = new OrderLineDTO(
                    $orderLine['id'],
                    $orderLine['product_item_id'],
                    $product['name'],
                    $item['size'],
                    $item['color'],
                    $orderLine['qty'],
                    $orderLine['price'],
                    $itemImage
                );
                $orderLines[] = $orderLineDTO->toArray();
            }
            $orderDTO = new OrderDTO(
                $result['id'],
                $result['shipping_address'],
                $result['name'],
                $result['phone'],
                $result['email'],
                $result['payment_method'],
                $result['shipping_method'],
                $result['user_id'],
                $result['order_total'],
                $result['order_date'],
                $result['order_status'],
                $orderLines
            );
            $all[] = $orderDTO->toArray();
        }

        return  $all;
    }

    public function getAll()
    {
        $result = ShopOrder::with('order_lines')->orderByDesc('id')->get();
        foreach ($result as $order) {
            $orderLines = [];
            foreach ($order['order_lines'] as $orderLine) {

                $product = ProductItem::where('id', $orderLine['product_item_id'])->with('product')->first()['product'];
                //return $product;
                $itemImage = ProductItem::where('id', $orderLine['product_item_id'])->with('product_item_images')->first()['product_item_images'][0]['url'];
                //return $itemImage;
                $item = ProductItem::where('id', $orderLine['product_item_id'])->first();

                $orderLineDTO = new OrderLineDTO(
                    $orderLine['id'],
                    $orderLine['product_item_id'],
                    $product['name'],
                    $item['size'],
                    $item['color'],
                    $orderLine['qty'],
                    $orderLine['price'],
                    $itemImage
                );
                $orderLines[] = $orderLineDTO->toArray();
            }

            $orderDTO = new OrderDTO(
                $order['id'],
                $order['shipping_address'],
                $order['name'],
                $order['phone'],
                $order['email'],
                $order['payment_method'],
                $order['shipping_method'],
                $order['user_id'],
                $order['order_total'],
                $order['order_date'],
                $order['order_status'],
                $orderLines
            );
            $orders[] = $orderDTO->toArray();
        }

        return  $orders;
    }

    public function updateStatus($orderId, $orderStatus)
    {
        $order = ShopOrder::find($orderId);
        $order->order_status = $orderStatus;
        $order->save();
        return $this->true;
    }

    public function cancelOrder($orderId, $orderStatus)
    {
        return $this->updateStatus($orderId, $orderStatus);
    }

    public function checkCartEmpty($userId)
    {
        $cart = ShoppingCart::where('user_id', $userId)->first();

        if (empty($cart)) {
            // create new cart for user
            $cart = ShoppingCart::create([
                'user_id' => $userId,
            ]);
        }
        // check if this cart is empty
        if (ShoppingCartItem::where('cart_id', $cart->id)->count() == 0) {
            return 0;
        }

        return 1;
    }

    public function create($userId, $AddOrderDTO)
    {
        $payment = $AddOrderDTO->getPaymentMethod();
        $cart = $this->checkCartEmpty($userId);
        if ($cart == 0) return "Cart is empty";
        else $cart = ShoppingCart::where('user_id', $userId)->first();

        $res = $this->cartToOrderLine($cart->id, $userId, $AddOrderDTO);
        $order = $res[0];
        $itemIds = $res[1];
        // DB::beginTransaction();
        try {
            switch ($payment) {

                case 0: // ship cod
                    //DB::commit();
                    $this->decreaseProductQty($cart->id);
                    $this->deleteCart($cart->id);
                    return ("Order successfully");
                    break;

                    //TODO: do momo payment methd (why my laptop crash when i run this code)
                case 1: // Momo

                    $payUrl = $this->momoCreatePayment($order->id, $order->order_total);

                    // DB::commit();
                    // redirect to payurl
                    //return redirect()->away($payUrl);

                    return $payUrl;
                    break;

                case 2: // vnpay is ok now

                    $payUrl = $this->vnpayCreatePayment($order->id, $order->order_total);

                    //TODO when return this url, product_item qty_in_stock will decrease temporarily
                    // if user payment is successful, then decrease qty_in_stock permanently
                    // else rollback qty_in_stock

                    // We will check if the payment is successful or not in the confirmPayment method

                    foreach ($itemIds as $itemId => $qty) {
                        try {
                            $productItem = ProductItem::where('id', $itemId)->lockForUpdate()->first(); // Use lockForUpdate to prevent race conditions
                            if ($productItem) {
                                $productItem->qty_in_stock -= $qty;
                                $productItem->save();

                                Redis::set("{$userId}:{$itemId}", $qty);

                                // Everything is successful
                            } else {
                                // Handle the case when the product item is not found
                            }
                        } catch (\Exception $e) {
                            // Handle the exception, log, or throw an error
                        }
                    }

                    //DB::commit();
                    return $payUrl;
                    break;
                default:
                    return "Payment method is not valid";
            }
        } catch (\Exception $e) {
            // DB::rollBack();
            // Handle the exception, log, or throw an error
        }
    }



    public function decreaseProductQty($cartId)
    {
        $shoppingCartItems = ShoppingCartItem::where('cart_id', $cartId)->get(['product_item_id', 'qty']);

        foreach ($shoppingCartItems as $cartItem) {
            $productItem = ProductItem::where('id', $cartItem['product_item_id'])->first(['qty_in_stock', 'id']);

            if ($productItem->qty_in_stock < $cartItem['qty']) {
                return response("Not enough qty in stock for item id" . $productItem->id, 400);
            } else {
                $productItem->qty_in_stock -= $cartItem['qty'];
                //return $productItem->qty_in_stock;
                $productItem->save();
                return null; // Return null or a success message if needed
            }
        }
    }

    public function deleteCart($cartId)
    {
        $shoppingCartItems = ShoppingCartItem::where('cart_id', $cartId)->get(['product_item_id', 'qty']);

        ShoppingCartItem::where('cart_id', $cartId)->delete();
        return response()->json(["message" => "Order successfully"]);
    }

    // add all item in cart to orderline
    public function cartToOrderLine($cartId, $userId, $AddOrderDTO)
    {

        $order = $this->createOrder($userId, $AddOrderDTO);

        $itemIds = $this->createOrderLines($cartId, $order);
        return [$order, $itemIds];
    }

    private function createOrder($userId, $AddOrderDTO)
    {
        return ShopOrder::create([
            'shipping_address' => $AddOrderDTO->getShippingAddress(),
            'name' => $AddOrderDTO->getName(),
            'phone' => $AddOrderDTO->getPhone(),
            'email' => $AddOrderDTO->getEmail(),
            'payment_method' => $AddOrderDTO->getPaymentMethod(),
            'shipping_method' => $AddOrderDTO->getShippingMethod(),
            'user_id' => $userId,
            'order_total' => 0,
            'order_date' => date("Y-m-d"),
            'order_status' => 0,
        ]);
    }

    private function createOrderLines($cartId, $order)
    {
        $shoppingCartItems = ShoppingCartItem::where('cart_id', $cartId)->with('product_item.product')->get();
        $sum = 0;
        $orderLines = [];

        $itemIds = [];

        foreach ($shoppingCartItems as $cartItem) {
            $qty = $cartItem->qty;

            if ($cartItem->product_item->qty_in_stock < $qty) {
                return response($this->false . ' ' . $cartItem->product_item->product->name . ' is out of stock', 400);
            }

            $price = $cartItem->product_item->product->price_int;
            $sum += $qty * $price;

            $itemIds['' . $cartItem->product_item_id] = $qty;

            $orderLines[] = [
                'product_item_id' => $cartItem->product_item_id,
                'order_id' => $order->id,
                'qty' => $qty,
                'price' => $price,
            ];
        }

        $order->order_total = $sum;
        $order->save();

        OrderLine::insert($orderLines);

        return $itemIds;
    }


    public function momoExecPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }


    public function momoCreatePayment($orderId, $amount)
    {
        $billId = $orderId . ':' . time();

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $amount;
        $orderId = $orderId;
        $redirectUrl = "http://localhost:3000/payment/momo";
        $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
        $extraData = "";
        $requestId = time() . "";
        $requestType = "captureWallet";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        // Rest of your code...

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount * 100,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->momoCreatePayment($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true); // decode json

        //header('Location: ' . $jsonResult['payUrl']);
        if ($jsonResult['message'] == 'Success')
            return $jsonResult['payUrl'];
        else return $jsonResult['message'];
    }


    public function vnpayCreatePayment($orderId, $amount)
    {

        $billId = $orderId . ':' . time();

        $vnp_TmnCode = env('VNPAY_TMNCODE'); //Mã website tại VNPAY 
        $vnp_HashSecret = env('VNPAY_HASHSECRET'); //Chuỗi bí mật
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:3000/payment/vnpay";

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


    public function vnpayConfirmPayment($inputData)
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
                    // $order = NULL; // Replace with logic to fetch order data by $orderId from your database
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
                                $orderLines = OrderLine::where('order_id', $orderId)->get();
                                if ($status == 1) {
                                    $order = ShopOrder::where('id', $orderId)->first();
                                    $order->order_status = 1;
                                    $order->save();

                                    $this->deleteCart(ShoppingCart::where('user_id', $order->user_id)->first()['id']);

                                    // get all item of this order and delete from redis

                                    foreach ($orderLines as $orderLine) {
                                        Redis::del("{$order->user_id}:{$orderLine->product_item_id}");
                                    }
                                } else {
                                    //restore the item qty_in_stock
                                    foreach ($orderLines as $orderLine) {

                                        $productItem = ProductItem::where('id', $orderLine->product_item_id)->first();
                                        // increase qty in stock using redis
                                        $productItem->qty_in_stock += Redis::get("{$order->user_id}:{$orderLine->product_item_id}");
                                        Redis::del("{$order->user_id}:{$orderLine->product_item_id}");
                                        $productItem->save();
                                    }
                                    // delete shop_order
                                    ShopOrder::where('id', $orderId)->delete();

                                    $returnData['RspCode'] = '-1';
                                    $returnData['Message'] = 'Payment cancelled';
                                    return $returnData;
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
