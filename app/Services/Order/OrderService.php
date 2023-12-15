<?php

namespace App\Services\Order;

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


class OrderService
{
    private $true = "ok 200";
    private $false = "error 400";

    public function __construct(private MomoService $momoService, private VnPayService $vnpayService)
    {
    }

    public function get($userId)
    {
        return 1;
        // $result = ShopOrder::where('user_id', $userId)->with('order_lines')->first();
        // //return  $result;  
        // $orderLines = [];
        // foreach ($result['order_lines'] as $orderLine) {

        //     $product = ProductItem::where('id', $orderLine['product_item_id'])->with('product')->first()['product'];
        //     //return $product;
        //     $itemImage = ProductItem::where('id', $orderLine['product_item_id'])->with('product_item_images')->first()['product_item_images'][0]['url'];
        //     //return $itemImage;
        //     $item = ProductItem::where('id', $orderLine['product_item_id'])->first();

        //     $orderLineDTO = new OrderLineDTO(
        //         $orderLine['id'],
        //         $orderLine['product_item_id'],
        //         $product['name'],
        //         $item['size'],
        //         $item['color'],
        //         $orderLine['qty'],
        //         $orderLine['price'],
        //         $itemImage
        //     );
        //     $orderLines[] = $orderLineDTO->toArray();
        // }

        // $orderDTO = new OrderDTO(
        //     $result['id'],
        //     $result['shipping_address'],
        //     $result['name'],
        //     $result['phone'],
        //     $result['email'],
        //     $result['payment_method'],
        //     $result['shipping_method'],
        //     $result['user_id'],
        //     $result['order_total'],
        //     $result['order_date'],
        //     $result['order_status'],
        //     $orderLines
        // );

        // return  $orderDTO->toArray();
    }

    public function getAll()
    {
        return 1;
        // $result = ShopOrder::with('order_lines')->get();
        
        // return  $result;  
        // foreach ($result as $order) {
        //     $orderLines = [];
        //     foreach ($order['order_lines'] as $orderLine) {

        //         $product = ProductItem::where('id', $orderLine['product_item_id'])->with('product')->first()['product'];
        //         //return $product;
        //         $itemImage = ProductItem::where('id', $orderLine['product_item_id'])->with('product_item_images')->first()['product_item_images'][0]['url'];
        //         //return $itemImage;
        //         $item = ProductItem::where('id', $orderLine['product_item_id'])->first();

        //         $orderLineDTO = new OrderLineDTO(
        //             $orderLine['id'],
        //             $orderLine['product_item_id'],
        //             $product['name'],
        //             $item['size'],
        //             $item['color'],
        //             $orderLine['qty'],
        //             $orderLine['price'],
        //             $itemImage
        //         );
        //         $orderLines[] = $orderLineDTO->toArray();
        //     }

        //     $orderDTO = new OrderDTO(
        //         $order['id'],
        //         $order['shipping_address'],
        //         $order['name'],
        //         $order['phone'],
        //         $order['email'],
        //         $order['payment_method'],
        //         $order['shipping_method'],
        //         $order['user_id'],
        //         $order['order_total'],
        //         $order['order_date'],
        //         $order['order_status'],
        //         $orderLines
        //     );
        //     $orders[] = $orderDTO->toArray();
        // }

        // return  $orders;
    }

    public function updateStatus($orderId, $orderStatus)
    {
        $order = ShopOrder::find($orderId);
        $order->order_status = $orderStatus;
        $order->save();
        return $this->true;
    }

    public function create($userId, $AddOrderDTO)
    {
        // return 1;

        $payment = $AddOrderDTO->getPaymentMethod();

        $order = $this->cartToOrderLine($userId, $AddOrderDTO);

        switch ($payment) {

            case 0: // ship cod
                
                $this->deleteCart(ShoppingCart::where('user_id', $userId)->first()['id']);
                return $this->true.' payment method is ship cod';
                break;

                //TODO: do momo payment methd (why my laptop crash when i run this code)
            case 1: // Momo

                $payUrl = $this->momoService->createPayment($order->id, $order->order_total);
                return $payUrl;
                break;

            case 2: // vnpay is ok now

                $payUrl = $this->vnpayService->createPayment($order->id, $order->order_total);
                return $payUrl;
                break;
        }
    }



    public function deleteCart($cartId)
    {
        ShoppingCartItem::where('cart_id', $cartId)->delete();
        return $this->true;
    }

    public function  cartToOrderLine($userId, $AddOrderDTO)
    {
        // add all item in cart to orderline
        $cartId = ShoppingCart::where('user_id', $userId)->first()['id'];

        // add new order
        $order = ShopOrder::create(
            [
                'shipping_address' => $AddOrderDTO->getShippingAddress(),
                'name' => $AddOrderDTO->getName(),
                'phone' => $AddOrderDTO->getPhone(),
                'email' => $AddOrderDTO->getEmail(),
                'payment_method' => $AddOrderDTO->getPaymentMethod(),
                'shipping_method' => $AddOrderDTO->getShippingMethod(),
                'user_id' => $userId,
                'order_total' => 0,
                'order_date' => date("Y-m-d"),
                'order_status' => 0
            ]
        );

        $shoppingCartItems = ShoppingCartItem::where('cart_id', $cartId)->with('product_item.product')->get();

        $sum = 0;
        //return $shoppingCartItems;
        // Loop through each shopping cart item and prepare data for insertion into the order line
        $orderLines = [];
        foreach ($shoppingCartItems as $cartItem) {
            $qty = $cartItem->qty;
            $price = $cartItem->product_item->product->price_int;
            $sum += $qty * $price;

            $orderLines[] = [
                'product_item_id' => $cartItem->product_item_id,
                'order_id' => $order->id,
                'qty' => $qty,
                'price' => $price, // Assuming price is retrieved from the related ProductItem model
            ];
        }
        //update order total
        $order->order_total = $sum;
        $order->save();

        $orderLine = OrderLine::insert($orderLines);

        return $order;
    }
}
