<?php

namespace App\Mapper\Payment;

use App\Responses\IResponse;
use App\Responses\Payment\GetPos\GetPosResponse;
use App\Responses\Payment\GetPos\PosResponse;
use Psr\Http\Message\ResponseInterface;

class GetPosMapper
{
    public static function map(ResponseInterface $response): IResponse
    {
        $getPosResponse = new GetPosResponse();

        $responseData = json_decode($response->getBody(), true);

        $getPosResponse->setStatusCode($responseData['status_code']);
        $getPosResponse->setStatusDescription($responseData['status_description']);

        $posResponses = [];
        foreach ($responseData['data'] as $response)
        {
            $posResponse = new PosResponse();
            $posResponse->setPosId($response['pos_id']);
            $posResponse->setCampaignId($response['campaign_id']);
            $posResponse->setAllocationId($response['allocation_id']);
            $posResponse->setInstallmentsNumber($response['installments_number']);
            $posResponse->setCardType($response['card_type']);
            $posResponse->setCardProgram($response['card_program']);
            $posResponse->setCardScheme($response['card_scheme']);
            $posResponse->setPayableAmount($response['payable_amount']);
            $posResponse->setHashKey($response['hash_key']);
            $posResponse->setAmountToBePaid($response['amount_to_be_paid']);
            $posResponse->setCurrencyCode($response['currency_code']);
            $posResponse->setCurrencyId($response['currency_id']);
            $posResponse->setTitle($response['title']);

            $posResponses[] = $posResponse;

        }
        $getPosResponse->setData($posResponses);

        return $getPosResponse;
    }
}
