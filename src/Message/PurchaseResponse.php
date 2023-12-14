<?php


namespace Omnipay\Meeg\Message;

use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return isset($this->data['success']) && $this->data['success'] == 1;
    }

    public function getToken(){
        return $this->isSuccessful() ? $this->data['data']['token'] : null;
    }

}
