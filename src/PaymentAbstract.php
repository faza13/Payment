<?php


namespace Faza13\Payment;


use Illuminate\Support\Carbon;
use Illuminate\Support\Manager;

abstract class PaymentAbstract
{
    public $customerInfo;

    public $orderDetails;

    public $paymentType;

    public $items;

    public $expire;

    public function __construct()
    {
        $this->setExpire(config('payment.expiry_in'));
    }

    public function setExpire($minutes)
    {
        $this->expire = $minutes;

        return $this;
    }

    public function expireAt() :Carbon
    {

        return Carbon::now()->addMinutes($this->expire);
    }

}
