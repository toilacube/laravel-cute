<?php
namespace App\DTOs\Requests;
class AddOrderDTO
{
    private $shippingAddress;
    private $paymentMethod;
    private $shippingMethod;

    public function __construct($shippingAddress, $paymentMethod, $shippingMethod)
    {
        $this->shippingAddress = $shippingAddress;
        $this->paymentMethod = $paymentMethod;
        $this->shippingMethod = $shippingMethod;
    }

    // Getters and setters for each property if needed
    // For simplicity, using public properties here

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod($shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;
    }
}
