<?php

namespace App\Requests\Payment;

class Payment2dRequest extends BasePaymentRequest
{
    /**
     * @var integer
     */
    private int $cvv;

    /**
     * @return int
     */
    public function getCvv(): int
    {
        return $this->cvv;
    }

    /**
     * @param int $cvv
     */
    public function setCvv(int $cvv): void
    {
        $this->cvv = $cvv;
    }

    /**
     * @return false|string
     */
    public function toJson(): bool|string
    {
        $parent = parent::toArray();

        $child = get_object_vars($this);

        $data = array_merge($parent, $child);
        return json_encode($data);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $parent = parent::toArray();

        $child = get_object_vars($this);

        $data = array_merge($parent, $child);
        return $data;

    }

//    public function getData()
//    {
//        $data = [
//            'cc_holder_name' => $this->getCcHolderName(),
//            'cc_no' => $this->getCcNo(),
//            'expiry_month' => $this->getExpiryMonth(),
//            'expiry_year' => $this->getExpiryYear(),
//            'cvv' => $this->getCvv(),
//            'currency_code' => $this->getCurrencyCode(),
//            'installments_number' => $this->getInstallmentsNumber(),
//            'invoice_id' => $this->getInvoiceId(),
//            'invoice_description' => $this->getInvoiceDescription(),
//            'total' => $this->getTotal(),
//            'merchant_key' => $this->getMerchantKey(),
//            'name' => $this->getName(),
//            'surname' => $this->getSurname(),
//            'hash_key' => $this->getHashKey(),
//            'items' => $this->getItems()
//        ];
//
//        return json_encode($data);
//    }

}
