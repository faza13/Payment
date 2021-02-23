<?php

namespace Faza13\Payment\Contract;


interface PaymentInterface
{

    public function setCustomerInfo($customerInfo);

    public function setOrderDetails($orderDetails);

    public function setOrderItems(array $items);

    public function send(string $type, string $issuer, array $params = []);

}
