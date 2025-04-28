<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class TransactionResultResponse extends Response
{
    public function isPending(): bool
    {
        return (int) $this->data['orderStatus'] === 0;
    }

    public function getTransactionReference(): ?string
    {
        foreach ((array) @$this->data['attributes'] as $attribute) {
            if ($attribute['name'] === 'mdOrder') {
                return $attribute['value'];
            }
        }

        return null;
    }
}
