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
        $serverKey = $this->app['config']['payment.env'] ?? '';
        $clientKey = $this->app['config']['payment.env'] ?? '';

        if(!$serverKey && !$clientKey)
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
        $secretKey = $this->app['config']['payment.secret_key'];

        if(!$secretKey)
            throw new \InvalidArgumentException("Secret key is empty");

        return new XenditManager($env, $secretKey);
    }

    public function getDefaultDriver(): string
    {
        return $this->app['config']['payment.default'];
    }
}
