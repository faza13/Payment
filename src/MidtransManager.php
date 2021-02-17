<?php


namespace Faza13\Payment;


use Faza13\Payment\Contract\PaymentInterface;

class MidtransManager extends PaymentAbstract implements PaymentInterface
{
    public function __construct($env, $serverKey, $clientKey)
    {
        // set config midtrans
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$clientKey = $clientKey;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        \Midtrans\Config::$isProduction = (bool) $env;
    }


    public function setCustomerInfo($customerInfo): MidtransManager
    {

//        [
//            'first_name'    => $this->first_name,
//            'last_name'     => $this->last_name,
//            'email'         => $this->email,
//            'phone'         => $this->phone,
////            'billing_address'  => array(),
//            'shipping_address' => $this->getShippingAddress()
//        ];
        $this->customerInfo = $customerInfo;
        return $this;
    }

    public function setOrderDetails($orderDetails): MidtransManager
    {
//        [
//            'id' => $this->order_cid.'-shipping',
//            'price' => $this->shipping_cost,
//            'quantity' => 1,
//            'name' => $this->shipping_service
//        ];
        $this->orderDetails = $orderDetails;
        return $this;
    }

    public function setPaymentDetail($paymentDetail): MidtransManager
    {
        $this->paymentDetail = $paymentDetail;
        return $this;
    }

    public function sendPayment($channel, $issuer)
    {
        // TODO: Implement sendPayment() method.
    }
}
