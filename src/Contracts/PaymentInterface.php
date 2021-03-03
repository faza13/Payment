<?php

namespace Faza13\Payment\Contracts;


interface PaymentInterface
{

    public function send($params = []);

    public function checkPayment(string $transId);

}
