<?php


namespace Omnipay\Meeg\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }


    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function getTx()
    {
        return $this->getParameter('tx');
    }
    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function setTx($value)
    {
        return $this->setParameter('tx', $value);
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getNetwork()
    {
        return $this->getParameter('network');
    }

    public function setNetwork($value)
    {
        return $this->setParameter('network', $value);
    }


    public function getData()
    {
        $data = [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'order_num' => $this->getTx(),
            'token' => $this->getToken(),
            'network' => $this->getNetwork()
        ];

        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }

    public function logoutAccountToken($token){
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];
        $httpResponse = $this->httpClient->request('POST', 'https://api.meeg.io/auth/logout', $headers);
    }

    public function authAccountToken(){
        $headers = [
            'Content-Type' => 'application/json',
        ];

        $postData = json_encode([
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
        ]);


        $httpResponse = $this->httpClient->request('POST', 'https://api.meeg.io/auth/login', $headers, $postData);
        $responseData = json_decode($httpResponse->getBody()->getContents(), true);
        return $responseData['data']['token'];

    }
    public function getUuid($token)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];


        $httpResponse = $this->httpClient->request('GET', 'https://api.meeg.io/merchants', $headers);
        $responseArray = json_decode($httpResponse->getBody()->getContents(), true);
        if (isset($responseArray['success']) && $responseArray['success'] == 1) {
            if (isset($responseArray['data']) && is_array($responseArray['data'])) {
                if (count($responseArray['data']) > 0 && is_array($responseArray['data'][0])) {
                    if (isset($responseArray['data'][0]['uuid'])) {
                        return $responseArray['data'][0]['uuid'];
                    }
                }
            }
        }
        return null;

    }

    public function createInvoices($token, $uuid){
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $postData = json_encode([
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'order_num' => $this->getTx(),
        ] );


        $httpResponse = $this->httpClient->request('POST', 'https://api.meeg.io/merchants/'.$uuid.'/invoices', $headers, $postData);
        $responseArray = json_decode($httpResponse->getBody()->getContents(), true);

        if (isset($responseArray['success']) && $responseArray['success'] == 1) {
            if (isset($responseArray['data'])) {
                if (isset($responseArray['data']['uuid'])) {
                    return $responseArray['data']['uuid'];
                }
            }
        }
        return null;


    }
    public function sendData($data)
    {
        $token = $this->authAccountToken();
        $uuid = $this->getUuid($token);
        $uuid_invoices = $this->createInvoices($token, $uuid);

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $postData = json_encode([
            'token' => $this->getToken(),
            'network' => $this->getNetwork()
        ] );


        $httpResponse = $this->httpClient->request('PATCH', 'https://api.meeg.io/public/invoices/'.$uuid_invoices.'/currency', $headers, $postData);
        $this->logoutAccountToken($token);
        return $this->createResponse($httpResponse->getBody()->getContents());

    }


    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, json_decode($data, true));
    }

}

