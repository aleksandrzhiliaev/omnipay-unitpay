<?php

namespace Omnipay\Unitpay\Message;


class PurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('description', 'amount', 'currency');

        $data['account'] = $this->getTransactionId();
        $data['sum'] = $this->getAmount();
        $data['desc'] = $this->getDescription();
        $data['currency'] = $this->getCurrency();
        $data['publicKey'] = $this->getPublicKey();
        $data['signature'] = $this->generateSignature($data['account'], $data['currency'], $data['desc'], $data['sum'], $this->getSecretKey());

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data, $this->getEndpoint());
    }

    public function generateSignature($account, $currency, $desc, $sum, $secretKey)
    {
        $hashStr = $account.'{up}'.$currency.'{up}'.$desc.'{up}'.$sum.'{up}'.$secretKey;

        return hash('sha256', $hashStr);
    }
}
