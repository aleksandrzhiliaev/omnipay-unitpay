<?php

namespace Omnipay\Unitpay\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{

    /**
     *
     * @var PurchaseRequest
     *
     */
    private $request;

    protected function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setPublicKey('Account');
        $this->request->setSecretKey('AccountName');
        $this->request->setCurrency('Currency');
        $this->request->setDescription('Description');
        $this->request->setAmount('10.00');
        $this->request->setTransactionId(1);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $expectedData = [
            'account' => 1,
            'sum' => '10.00',
            'desc' => 'Description',
            'currency' => 'CURRENCY',
            'publicKey' => 'Account',
            'signature' => '174c500cd5080e4ff9e8cd23bc6918c4e75a21ce56a771425b5cca2c1c394f01',
        ];

        $this->assertEquals($expectedData, $data);
    }

    public function testSendSuccess()
    {
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://unitpay.ru/pay/Account', $response->getRedirectUrl());
        $this->assertEquals('POST', $response->getRedirectMethod());
    }


}