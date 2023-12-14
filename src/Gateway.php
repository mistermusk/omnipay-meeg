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

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }


    public function moderated(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Meeg\Message\ModeratePayoutRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setPassword($this->getPassword());
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Meeg\Message\PurchaseRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setPassword($this->getPassword());
    }

    public function payout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Meeg\Message\PayoutRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setPassword($this->getPassword());
    }
}
