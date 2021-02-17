<?php

namespace Faza13\Payment\Contract;


interface PaymentInterface
{

    public function setCustomerInfo($customerInfo);

    public function setOrderDetails($orderDetails);

    public function setPaymentDetail($paymentDetail);

    public function sendPayment();

}
