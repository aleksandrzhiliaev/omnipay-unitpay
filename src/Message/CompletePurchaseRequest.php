<?php

namespace Omnipay\Unitpay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {

        $theirHash = $this->httpRequest->get('params')['signature'];
        $method = (string)$this->httpRequest->get('method');
        $ourHash = $this->getSignature($method, $this->httpRequest->get('params'), $this->getSecretKey());


        if ($theirHash !== $ourHash) {
            throw new InvalidResponseException("Callback hash does not match expected value");
        }

        return $this->httpRequest->get('params');
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }


    public function getSignature($method, $params, $secretKey)
    {
        ksort($params);
        unset($params['sign']);
        unset($params['signature']);
        array_push($params, $secretKey);
        array_unshift($params, $method);

        return hash('sha256', join('{up}', $params));
    }
}
