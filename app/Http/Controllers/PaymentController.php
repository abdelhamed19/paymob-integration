<?php

namespace App\Http\Controllers;


use App\Services\HandlePaymobIntegration;

class PaymentController extends Controller
{
    private $handlePaymobIntegration;
    public function __construct(HandlePaymobIntegration $handlePaymobIntegration)
    {
        $this->handlePaymobIntegration = $handlePaymobIntegration;
    }
    public function credit()
    {
        return $this->handlePaymobIntegration->credit();
    }
    public function getToken() {
        return $this->handlePaymobIntegration->getToken();
    }
    public function createOrder() {
        return $this->handlePaymobIntegration->createOrder();
    }
    public function getPaymentToken()
    {
        return $this->handlePaymobIntegration->getPaymentToken();
    }
}
