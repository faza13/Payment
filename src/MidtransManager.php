<?php


namespace Faza13\Payment;


use Carbon\Carbon;
use Faza13\Payment\Contracts\OrderPaymentInterface;
use Faza13\Payment\Contracts\PaymentInterface;
use Illuminate\Support\Str;
use Midtrans\CoreApi;
use Midtrans\Transaction;

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

    public function setOrderItems(array $items): MidtransManager
    {
        $this->items = $items;
        return $this;
    }

    public function send($params = [])
    {

        $method = Str::camel($this->paymentType);

        return $this->{$method}($params);


        $result = CoreApi::charge($req);

        $resp = ['result' => $result];
        switch ($type)
        {
            case 'bank_transfer':
                $resp['va_namuber'] = $this->getVaNumber($result, $issuer);
                break;
            case 'qris':
                $resp['qr_image'] = $result->actions[0]->url;
                break;
        }

        return $resp;
    }

    public function getVaNumber($result, $issuer)
    {
        $vaNumber = '';
        switch (strtolower($issuer))
        {
            case 'permata':
                $vaNumber = $result->permata_va_number;
                break;
            case 'mandiri':
                $vaNumber = "{$result->biller_code}|{$result->bill_key}";
                break;
            case 'bca':
            case 'bni':
            case 'bri':
                $vaNumber = collect($result->va_numbers)->first()->va_number;
                break;
        }

        return $vaNumber;
    }

    public function bankTransfer($params = [])
    {
        $transaction = [
            'transaction_details' => $this->orderDetails,
            'customer_details' => $this->customerInfo,
            'item_details' => $this->items,
            'custom_expiry' => [
                "order_time"=> Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y-m-d H:i:s O'),
                "expiry_duration"=> $this->expire,
                "unit"=> "minutes"
            ],
        ];


        if($this->issuer == 'mandiri') {
            return $this->eChannel($transaction, $params);
        }

        $transaction['bank_transfer'] = [
            'bank' => $this->issuer,
        ];
    }

    public function eChannel($transaction, $params = [])
    {
        $transaction['payment_type'] = 'echannel';
        $transaction['echannel'] = [
            "bill_info1" => "Payment For:",
            "bill_info2" => $transaction['transaction_details']['order_id'],
            "bill_info3" => "Name:",
            "bill_info4" => $transaction['customer_details']['first_name'],
        ];

        return $transaction;
    }

    Public function qris()
    {

        $transaction = [
            'transaction_details' => $this->orderDetails,
            'customer_details' => $this->customerInfo,
            'item_details' => $this->items,
            'custom_expiry' => [
                "order_time"=> Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y-m-d H:i:s O'),
                "expiry_duration"=> $this->expire,
                "unit"=> "minutes"
            ],
            "qris" => [
                "acquirer" => "gopay",
            ]
        ];

        return $transaction;
    }

    public function checkPayment(string $transactionId, $type = '')
    {
        $result = Transaction::status($transactionId);

        return [
            "status" => strtolower($result->transaction_status),
            "fraud" => strtolower($result->fraud_status),
            'result' => $result,
        ];
    }
}
