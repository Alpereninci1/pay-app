<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class RequestHelper
{
    public static function payment3dRequest($payment3dRequest,$data)
    {
        self::extracted($payment3dRequest, $data);
        $payment3dRequest->setReturnUrl(getenv('RETURN_URL'));
        $payment3dRequest->setCancelUrl(getenv('CANCEL_URL'));
    }

    public static function payment2dRequest($payment2dRequest,$data)
    {
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
        $name = $data['name'];
        $names = explode(' ', $name);
        $lastName = array_pop($names);
        $firstName = implode(' ',$names);

        $request->setCcHolderName($data['cc_holder_name']);
        $request->setCcNo($data['cc_no']);
        $request->setExpiryMonth($data['expiry_month']);
        $request->setExpiryYear($data['expiry_year']);
        $request->setMerchantKey(getenv('MERCHANT_KEY'));
        $request->setCurrencyCode(getenv('CURRENCY_CODE'));
        $request->setInvoiceDescription(getenv('INVOICE_DESCRIPTION'));
        $request->setName($firstName);
        $request->setSurname($lastName);
        $request->setTotal((float)$data['total']);
        $request->setInstallmentsNumber($data['installments_number']);
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
