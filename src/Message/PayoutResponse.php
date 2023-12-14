<?php

namespace Omnipay\InterKassa\Message;

use Omnipay\Common\Message\AbstractResponse;

class PayoutResponse extends AbstractResponse
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
