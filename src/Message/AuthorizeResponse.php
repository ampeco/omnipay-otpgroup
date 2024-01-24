<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class AuthorizeResponse extends Response
{
    use RegisteredOrder;

    public function getOrderId()
    {
        return $this->data['orderId'];
    }
}
