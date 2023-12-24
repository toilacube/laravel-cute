<?php

namespace App\DTOs\Requests;

class AddOrderDTO
{
    private $shippingAddress;
    private $paymentMethod;
    private $shippingMethod;
    private $name;
    private $phone;
    private $email;

    public function __construct($shippingAddress, $name, $phone, $email, $paymentMethod, $shippingMethod,)
    {
        $this->shippingAddress = $shippingAddress;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->paymentMethod = $paymentMethod;
        $this->shippingMethod = $shippingMethod;
    }


    // Getters and setters for each property if needed
    // For simplicity, using public properties here

    public function getName()
    {
        return $this->name;
    }
    //get mail
    public function getEmail()
    {
        return $this->email;
    }
    //get phone
    public function getPhone()
    {
        return $this->phone;
    }

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
