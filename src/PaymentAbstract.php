<?php


namespace Faza13\Payment;


use Illuminate\Support\Manager;

abstract class PaymentAbstract
{
    public $customerInfo;

    public $orderDetails;

    public $paymentType;

    public $paymentDetail;

}
