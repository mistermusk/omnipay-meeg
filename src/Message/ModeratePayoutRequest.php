<?php

namespace Omnipay\PayPlanet\Message;

use Omnipay\Common\Message\AbstractRequest;

class ModeratePayoutRequest extends AbstractRequest
{

    public function getEmail()
    {
        return $this->getParameter('email');
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

    public function getUuid()
    {
        return $this->getParameter('uuid');
    }

    public function setUuid($value)
    {
        return $this->setParameter('uuid', $value);
    }


    public function getData()
    {
        $data = [
            'uuid' => $this->getUuid(),
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
    public function sendData($data)
    {
        $token = $this->authAccountToken();
        $uuid = $this->getUuid();

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $httpResponse = $this->httpClient->request('PATCH', 'https://api.meeg.io/withdraw/'.$uuid.'/confirm', $headers);
        $this->logoutAccountToken($token);
        return $this->createResponse($httpResponse->getBody()->getContents());

    }

    protected function createResponse($data)
    {
        return $this->response = new ModeratePayoutResponse($this, json_decode($data, true));
    }

}
