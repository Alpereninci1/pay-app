<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class RequestHelper
{
    public static function payment3dRequest($payment3dRequest,$data)
    {
        $payment3dRequest->setCcHolderName($data['cc_holder_name']);
        $payment3dRequest->setCcNo($data['cc_no']);
        $payment3dRequest->setExpiryMonth($data['expiry_month']);
        $payment3dRequest->setExpiryYear($data['expiry_year']);
        self::extracted($payment3dRequest, $data);
        $payment3dRequest->setReturnUrl(getenv('RETURN_URL'));
        $payment3dRequest->setCancelUrl(getenv('CANCEL_URL'));
    }

    public static function payment2dRequest($payment2dRequest,$data)
    {
        $payment2dRequest->setCcHolderName($data['cc_holder_name']);
        $payment2dRequest->setCcNo($data['cc_no']);
        $payment2dRequest->setExpiryMonth($data['expiry_month']);
        $payment2dRequest->setExpiryYear($data['expiry_year']);
        $payment2dRequest->setCvv($data['cvv']);
        self::extracted($payment2dRequest, $data);

    }

    /**
     * @param $request
     * @param $data
     * @return void
     */
    public static function extracted($request, $data): void
    {
        $request->setMerchantKey(getenv('MERCHANT_KEY'));
        $request->setCurrencyCode(getenv('CURRENCY_CODE'));
        $request->setInvoiceDescription(getenv('INVOICE_DESCRIPTION'));
        $request->setTotal((float)$data['total']);
        $request->setInstallmentsNumber($data['installments_number']);
        $request->setName(Config::get('app._name'));
        $request->setSurname(Config::get('app.surname'));
        $request->setHashKey(HashGeneratorHelper::hashGenerator((float)$data['total'], $data['installments_number']));
        $request->setInvoiceId(Session::get('invoice_id'));
    }

    public static function getPosRequest($request,$data)
    {
        $request->setCreditCard($data['credit_card']);
        $request->setAmount($data['amount']);
        $request->setCurrencyCode(Config::get('app.currency_code'));
        $request->setIs2d(Config::get('app.is_2d'));
        $request->setMerchantKey(Config::get('app.merchant_key'));

    }
}
