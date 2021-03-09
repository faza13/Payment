<?php


namespace Faza13\Payment;


use Faza13\Payment\Contracts\OrderPaymentInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Manager;

abstract class PaymentAbstract
{
    public $customerInfo;

    public $orderDetails;

    public $paymentType;

    public $issuer;

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

    public function data(OrderPaymentInterface $order)
    {
        $this->customerInfo = $order->getCutomerDetail();

        $this->orderDetails = $order->getTransactionDetail();

        $this->issuer = $order->getIssuer();

        return $this;
    }

    public function type($type)
    {
        $this->paymentType = $type;

        return $this;
    }

    public function getPaymentChannel($driver)
    {

        $channel = collect($this->app['config']['payment.' . $driver . '.payment_type'])
            ->map(function($value){
                $value = collect($value)->filter(function (&$value){
                    return $value['status'] == true;
                });

                return $value;
            })
            ->filter(function($value){ return (count($value) > 0); });

        return $channel->toArray();
    }

}
