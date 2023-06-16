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
        $payment3dRequest->setMerchantKey(Config::get('app.merchant_key'));
        $payment3dRequest->setCurrencyCode(Config::get('app.currency_code'));
        $payment3dRequest->setInvoiceDescription(Config::get('app.invoice_description'));
        $payment3dRequest->setTotal((float)$data['total']);
        $payment3dRequest->setInstallmentsNumber($data['installments_number']);
        $payment3dRequest->setName(Config::get('app._name'));
        $payment3dRequest->setSurname(Config::get('app.surname'));
        $payment3dRequest->setHashKey(HashGeneratorHelper::hashGenerator((float)$data['total'], $data['installments_number']));
        $payment3dRequest->setInvoiceId(Session::get('invoice_id'));
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
        $payment2dRequest->setMerchantKey(Config::get('app.merchant_key'));
        $payment2dRequest->setCurrencyCode(Config::get('app.currency_code'));
        $payment2dRequest->setInvoiceDescription(Config::get('app.invoice_description'));
        $payment2dRequest->setTotal((float)$data['total']);
        $payment2dRequest->setInstallmentsNumber($data['installments_number']);
        $payment2dRequest->setName(Config::get('app._name'));
        $payment2dRequest->setSurname(Config::get('app.surname'));
        $payment2dRequest->setHashKey(HashGeneratorHelper::hashGenerator((float)$data['total'], $data['installments_number']));
        $payment2dRequest->setInvoiceId(Session::get('invoice_id'));

    }
}
