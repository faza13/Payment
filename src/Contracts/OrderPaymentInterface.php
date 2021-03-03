<?php


namespace Faza13\Payment\Contracts;


interface OrderPaymentInterface
{
    public function getTransactionDetail();

    public function getCutomerDetail();

    public function getShippingAddress();

    public function getIssuer();
}


//array:22 [
//  "id" => "ewc_26718e14-3ef8-4b14-9a3a-b40481b57fe8"
//  "business_id" => "5fd1b043e2ef597904e7d0e9"
//  "reference_id" => "DEV-FAZR20121243260"
//  "status" => "PENDING"
//  "currency" => "IDR"
//  "charge_amount" => 251000
//  "capture_amount" => 251000
//  "checkout_method" => "ONE_TIME_PAYMENT"
//  "channel_code" => "ID_OVO"
//  "channel_properties" => array:1 [▼
//    "mobile_number" => "+6281315272726"
//  ]
//  "actions" => null
//  "is_redirect_required" => false
//  "callback_url" => "http://stage.klamby.id/notification"
//  "created" => "2021-03-03T08:15:06.683Z"
//  "updated" => "2021-03-03T08:15:06.683Z"
//  "voided_at" => null
//  "capture_now" => true
//  "customer_id" => null
//  "payment_method_id" => null
//  "failure_code" => null
//  "basket" => null
//  "metadata" => null
//]
