<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class AuthorizeRequest extends PurchaseRequest
{

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return '/rest/registerPreAuth.do';
    }

    /**
     * @param array $data
     * @param int $statusCode
     * @return Response
     */
    protected function createResponse(array $data, int $statusCode): Response
    {
        return new AuthorizeResponse($this, $data, $statusCode);
    }
}
