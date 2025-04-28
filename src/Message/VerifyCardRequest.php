<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class VerifyCardRequest extends TransactionResultRequest
{
    protected function createResponse(array $data, int $statusCode): Response
    {
        return new VerifyCardResponse($this, $data, $statusCode);
    }
}