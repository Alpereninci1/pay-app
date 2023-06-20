<?php

namespace App\Mapper\Payment;

use App\Responses\IResponse;
use App\Responses\Payment\Payment2d\GetPayment2dResponse;
use App\Responses\Payment\Payment2d\Payment2dResponse;
use Psr\Http\Message\ResponseInterface;

class Payment2dMapper
{
    public static function map(ResponseInterface $response): IResponse
    {
        $getPayment2dResponse = new GetPayment2dResponse();

        $responseData = json_decode($response->getBody(), true);

        $getPayment2dResponse->setStatusCode($responseData['status_code']);
        $getPayment2dResponse->setStatusDescription($responseData['status_description']);

        $payment2dResponse = new Payment2dResponse();

        $payment2dResponse->setOrderNo($responseData['data']['order_no']);
        $payment2dResponse->setOrderId($responseData['data']['order_id']);
        $payment2dResponse->setInvoiceId($responseData['data']['invoice_id']);
        $payment2dResponse->setCreditCardNo($responseData['data']['credit_card_no']);
        $payment2dResponse->setTransactionType($responseData['data']['transaction_type']);
        $payment2dResponse->setPaymentStatus($responseData['data']['payment_status']);
        $payment2dResponse->setPaymentMethod($responseData['data']['payment_method']);
        $payment2dResponse->setErrorCode($responseData['data']['error_code']);
        $payment2dResponse->setError($responseData['data']['error']);
        $payment2dResponse->setAuthCode($responseData['data']['auth_code']);
        $payment2dResponse->setMerchantCommission($responseData['data']['merchant_commission']);
        $payment2dResponse->setUserCommission($responseData['data']['user_commission']);
        $payment2dResponse->setMerchantCommissionPercentage($responseData['data']['merchant_commission_percentage']);
        $payment2dResponse->setMerchantCommissionFixed($responseData['data']['merchant_commission_fixed']);
        $payment2dResponse->setHashKey($responseData['data']['hash_key']);
        $payment2dResponse->setOriginalBankErrorCode($responseData['data']['original_bank_error_code']);
        $payment2dResponse->setOriginalBankErrorDescription($responseData['data']['original_bank_error_description']);

        $getPayment2dResponse->setData($payment2dResponse);

        return $getPayment2dResponse;
    }
}
