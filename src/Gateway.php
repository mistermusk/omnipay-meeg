<?php

namespace Omnipay\Meeg;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Meeg';
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
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

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function isSignatureValidDeposit($sign, $data) {

        ksort($data);
        $sign_params_query = http_build_query($data);
        $calculatedSign = hash_hmac('sha256', $sign_params_query, $this->getSecret());
        return $sign === $calculatedSign;
    }

    public function isSignatureValidWithdrawal($sign, $data) {

        ksort($data);
        $sign_params_query = http_build_query($data);
        $calculatedSign = hash_hmac('sha256', $sign_params_query, $this->getSecret());
        return $sign === $calculatedSign;
    }


    public function moderated(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Meeg\Message\ModeratePayoutRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setSecret($this->getSecret())
            ->setPassword($this->getPassword());
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Meeg\Message\PurchaseRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setSecret($this->getSecret())
            ->setPassword($this->getPassword());
    }

    public function payout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Meeg\Message\PayoutRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setSecret($this->getSecret())
            ->setPassword($this->getPassword());
    }
}
