<?php


namespace Faza13\Payment;


use Faza13\Payment\Contract\PaymentInterface;
use Illuminate\Support\Str;
use Xendit\EWallets;
use Xendit\VirtualAccounts;

class XenditManager extends PaymentAbstract implements PaymentInterface
{


    public function __construct($env, $secretKey)
    {
        parent::__construct();

        Xendit::setApiKey($secretKey);
    }

    public function setCustomerInfo($customerInfo)
    {
        $this->customerInfo = $customerInfo;

        return $this;
    }

    public function setOrderDetails($orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    public function setOrderItems(array $items)
    {
//        'items' => [
//        [
//            'id' => '123123',
//            'name' => 'Phone Case',
//            'price' => 1000000,
//            'type' => 'Smartphone',
//            'url' => 'http://example.com/phone/phone_case',
//            'quantity' => 2
//        ]
//    ]

    //        [
//            'id' => $this->order_cid.'-shipping',
//            'price' => $this->shipping_cost,
//            'quantity' => 1,
//            'name' => $this->shipping_service
//        ];

//        is_single_use

        $this->items = $items;
        return $this;
    }

    public function send($type, $issuer, $params= [])
    {

        $method = Str::camel($type);

        return $this->{$method}($issuer, $params);
    }


    public function bankTransfer($issuer, $params= [])
    {
        //        [
//            "external_id" => "demo-1475804036622",
//            "bank_code" => "BNI",
//            "name" => "Rika Sutanto"
//        ]

        $name = "";

        if(isset($this->customerInfo['first_name']))
            $name = trim(strtoupper($this->customerInfo['first_name']) . " " . strtoupper($this->customerInfo['last_name']));

        if($this->customerInfo['last_name'])
            $name .= " " . strtoupper($this->customerInfo['last_name']);

        if($this->customerInfo['name'])
            $name = strtoupper($this->customerInfo['name']);


        $xenditParams['external_id'] = $this->orderDetails['order_cid'];
        $xenditParams['bank_code'] = $issuer;
        $xenditParams['name'] = trim($name);
        $xenditParams['amount'] = $this->orderDetails['gross_amount'];

        $xenditParams['is_closed'] = 1;
        $xenditParams['expected_amount'] = 1;
        $xenditParams['is_single_use'] = 1;
        $xenditParams['expiration_date'] = $this->expireAt()->utc()->toDateTimeString();

        $result = VirtualAccounts::create($params);
        return [
            'va_namuber' => $result['account_number'],
            'resp' => $result
        ];
    }

    public function ewallet($issuer, $params= [])
    {
        $xenditParams['reference_id'] = $this->orderDetails['order_cid'];
        $xenditParams['currency'] = 'IDR';
        $xenditParams['amount'] = $this->orderDetails['gross_amount'];
        $xenditParams['checkout_method'] = 'ONE_TIME_PAYMENT';
        $xenditParams['channel_code'] = 'ID_' . strtoupper($issuer);
        $xenditParams['channel_properties'] = strtoupper($issuer);
//        $xenditParams['channel_properties'] = strtoupper($issuer);

        $mobile = preg_replace('/^0|^62/', '+62', $this->customerInfo['phone']);

        switch ($issuer)
        {
            case 'ovo':
                $xenditParams['channel_properties'] = [
                    'mobile_number' => $mobile
                ];
                break;
            default:

                if(!$params['success_redirect_url'])
                    Throw new \InvalidArgumentException("success_redirect_url not set in params");


                $xenditParams['channel_properties'] = [
                    'success_redirect_url' => $params['success_redirect_url']
                ];
        }

        $result = EWallets::create($xenditParams);


        return [

            'resp' => $result,
        ];
    }

}
