<?php


namespace Faza13\Payment;


use Faza13\Payment\Contracts\OrderPaymentInterface;
use Faza13\Payment\Contracts\PaymentInterface;
use Illuminate\Support\Str;
use Xendit\EWallets;
use Xendit\Invoice;
use Xendit\VirtualAccounts;
use Xendit\Xendit;

class XenditManager extends PaymentAbstract implements PaymentInterface
{


    public function __construct($env, $secretKey)
    {
        parent::__construct();

        Xendit::setApiKey($secretKey);
    }

    public function send($params = []): array
    {
//        $orderDetail = $order->getTransactionDetail();
//        $customerInfo = $order->getCutomerDetail();

//        $params = [
//            'external_id' => $orderDetail['order_id'],
//            'payer_email' => $customerInfo['email'],
//            'description' => 'Payment for order ' . $orderDetail['order_id'],
//            'amount' => $orderDetail['gross_amount'],
//            "payment_methods" => [$order->getIssuer()]
//        ];

//        $result = Invoice::create($params);
//
//        return [
//            'result' => $result,
//            'transaction_id' => $result['id']
//        ];
        $method = Str::camel($this->paymentType);

        return $this->{$method}($params);
    }


//    public function checkPayment(string $transId)
//    {
//        $result = Invoice::retrieve();
//
//        if(strtoupper($result['status']) == 'SETTLED')
//        {
//            $result['status'] == strtoupper('settlement');
//        }
//
//        return [
//            'status' => $result['status'],
//            'result' => $result
//        ];
//    }


    public function bankTransfer($params= [])
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


        $xenditParams['external_id'] = $this->orderDetails['order_id'];
        $xenditParams['bank_code'] = $this->issuer;
        $xenditParams['name'] = trim($name);
        $xenditParams['amount'] = $this->orderDetails['gross_amount'];

        $xenditParams['is_closed'] = 1;
        $xenditParams['expected_amount'] = 1;
        $xenditParams['is_single_use'] = 1;
        $xenditParams['expiration_date'] = $this->expireAt()->utc()->toDateTimeString();

        $result = VirtualAccounts::create($params);
        return [
            'va_namuber' => $result['account_number'],
            'transaction_id' => $result[''],
            'resp' => $result
        ];
    }

    public function ewallet($params= [])
    {
        $xenditParams['reference_id'] = $this->orderDetails['order_id'];
        $xenditParams['currency'] = 'IDR';
        $xenditParams['amount'] = $this->orderDetails['gross_amount'];
        $xenditParams['checkout_method'] = 'ONE_TIME_PAYMENT';
        $xenditParams['channel_code'] = 'ID_' . strtoupper($this->issuer);
        $xenditParams['channel_properties'] = strtoupper($this->issuer);
        $xenditParams['ewallet_type'] = strtoupper($this->issuer);
//        $xenditParams['channel_properties'] = strtoupper($issuer);

//        dd($this->customerInfo);
        $mobile = str_replace("+", "", preg_replace('/^0|^62/', '', $this->customerInfo['phone']));

        switch ($this->issuer)
        {
            case 'ovo':
                $xenditParams['channel_properties'] = [
                    'mobile_number' => "+62" . $mobile
                ];
                break;
            default:

                if(!$params['success_redirect_url'])
                    Throw new \InvalidArgumentException("success_redirect_url not set in params");

                $xenditParams['channel_properties'] = [
                    'success_redirect_url' => $params['success_redirect_url']
                ];
        }

        $result = EWallets::createEWalletCharge($xenditParams);

        return [
            'transaction_id' => $result['id'],
            'result' => $result,
        ];
    }

    public function checkPayment(string $transId)
    {
        $result = null;
        $status = null;
        switch ($this->paymentType)
        {
////            case 'bank_transfer':
////                $result = V::getEWalletChargeStatus($transId);
////                break;
            case 'ewallet':
                $result = EWallets::getEWalletChargeStatus($transId);
                $status = $result['status'];
                switch ($result['status'])
                {
                    case "SUCCEEDED":
                        $status = "settlement";
                        break;
                    case "FAILED":
                        $status = "cancel";
                        break;
                    default:
                        $status = 'pending';
                }

                break;
        }

        return [
            "status" => $status,
            "result" => $result
        ];
    }
}
