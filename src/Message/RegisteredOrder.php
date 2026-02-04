<?php

namespace Ampeco\OmnipayOtpGroup\Message;

trait RegisteredOrder
{
    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return parent::isSuccessful()
            && array_key_exists('orderId', $this->data)
            && array_key_exists('formUrl', $this->data);
    }

    public function getRedirectUrl()
    {
        return $this->data['formUrl'] ?? null;
    }

    public function getTransactionReference()
    {
        return $this->data['orderId'] ?? null;
    }

    public function getOrderId()
    {
        return $this->data['orderId'] ?? null;
    }
}
