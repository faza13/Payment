<?php


namespace Faza13\Payment;

use Illuminate\Support\Manager;

class PaymentManager extends Manager
{

    /**
     * Get a driver instance.
     *
     * @param  string|null  $name
     * @return mixed
     */
    public function channel($name = null)
    {
        return $this->driver($name);
    }

    /**
     * Create a Nexmo SMS driver instance.
     *
     * @return MidtransManager
     */
    public function createMidtransDriver()
    {
        $env = $this->app['config']['payment.env'] ?? false;
        $serverKey = $this->app['config']['payment.midtrans.secret_key'] ?? '';
        $clientKey = $this->app['config']['payment.midtrans.secret_key'] ?? '';

        if(!$serverKey or !$clientKey)
            throw new \InvalidArgumentException("Server key or client key is empty");

        return new MidtransManager($env, $serverKey, $clientKey);
    }

    /**
     * Create a xendit payment gateway driver instance.
     *
     * @return XenditManager
     */
    public function createXenditDriver()
    {
        $env = $this->app['config']['payment.env'];
        $secretKey = $this->app['config']['payment.xendit.secret_key'];

        if(!$secretKey)
            throw new \InvalidArgumentException("Secret key is empty");

        return new XenditManager($env, $secretKey);
    }

    public function getDefaultDriver(): string
    {
        return $this->app['config']['payment.default'];
    }

    public function getPaymentChannel()
    {
        $channel = collect($this->app['config']['payment.' . $this->driver() . '.payment_type'])
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
