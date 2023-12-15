<?php
namespace App\DTOs\Responses;

class OrderDTO
{
    public int $id;
    public string $shippingAddress;
    private string $name;
    private string $phone;
    private string $email;
    public int $paymentMethod;
    public int $shippingMethod;
    public string $userId;
    public float $orderTotal;
    public string $orderDate;
    public int $orderStatus;
    public array $orderLines; // Array of OrderLineDTO objects

    public function __construct(
        int $id,
        string $shippingAddress,
        string $name,
        string $phone,
        string $email,
        int $paymentMethod,
        int $shippingMethod,
        string $userId,
        float $orderTotal,
        string $orderDate,
        int $orderStatus,
        array $orderLines
    ) {
        $this->id = $id;
        $this->shippingAddress = $shippingAddress;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->paymentMethod = $paymentMethod;
        $this->shippingMethod = $shippingMethod;
        $this->userId = $userId;
        $this->orderTotal = $orderTotal;
        $this->orderDate = $orderDate;
        $this->orderStatus = $orderStatus;
        $this->orderLines = $orderLines;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'shippingAddress' => $this->shippingAddress,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'paymentMethod' => $this->paymentMethod,
            'shippingMethod' => $this->shippingMethod,
            'userId' => $this->userId,
            'orderTotal' => $this->orderTotal,
            'orderDate' => $this->orderDate,
            'orderStatus' => $this->orderStatus,
            'orderLines' => $this->orderLines,
        ];
    }
}