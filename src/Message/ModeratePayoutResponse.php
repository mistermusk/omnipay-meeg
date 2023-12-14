<?php

namespace Omnipay\PayPlanet\Message;

use Omnipay\Common\Message\AbstractResponse;

class ModeratePayoutResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        if (isset($this->data['status'])) {
            if ($this->data['status']) {
                return true;
            }
        }
    }

    public function getMessage()
    {
        return isset($this->data['message']) ? json_encode($this->data['message']) : null;
    }

}
