<?php
namespace App\Services\Order;

use App\Models\OrderLine;
use App\Models\ShopOrder;
use App\Models\ProductItem;
use App\DTOs\Responses\OrderDTO;
use App\DTOs\Responses\OrderLineDTO;

class OrderService {
    private $true = "ok 200";
    private $false = "error 400";

    public function get($userId){
        $result = ShopOrder::where('user_id', $userId)->with('order_lines')->first();
        //return  $result;  
        $orderLines = [];
        foreach($result['order_lines'] as $orderLine){

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
            $result['payment_method'],
            $result['shipping_method'],
            $result['user_id'],
            $result['order_total'],
            $result['order_date'],
            $result['order_status'],
            $orderLines
        );

        return  $orderDTO->toArray() ;
    }

    public function getAll(){
        $result = ShopOrder::with('order_lines')->get();
        //return  $result;  
        foreach($result as $order){
            $orderLines = [];
            foreach($order['order_lines'] as $orderLine){
    
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

    public function updateStatus($orderId, $orderStatus){
        $order = ShopOrder::find($orderId);
        $order->order_status = $orderStatus;
        $order->save();
        return $this->true;
    }
}