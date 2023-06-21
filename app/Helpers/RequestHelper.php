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
        $payment3dRequest->setReturnUrl(Config::get('app.return_url'));
        $payment3dRequest->setCancelUrl(Config::get('app.cancel_url'));
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
     * TODO: parametre ismi burada yanlış tanımlanmış
     * @param $payment2dRequest
     * @param $data
     * @return void
     */
    public static function extracted($request, $data): void
    {
        $request->setMerchantKey(Config::get('app.merchant_key'));
        $request->setCurrencyCode(Config::get('app.currency_code'));
        $request->setInvoiceDescription(Config::get('app.invoice_description'));
        $request->setTotal((float)$data['total']);
        $request->setInstallmentsNumber($data['installments_number']);
        // TODO: name ve surname bilgisini neden configden alıyoruz
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