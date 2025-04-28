<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class TransactionResultRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return '/rest/getOrderStatusExtended.do';
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return new TransactionResultResponse($this, $data, $statusCode);
    }

    public function getData(): array
    {
        return array_merge(parent::getData(), [
            'orderId' => $this->getOrderId(),
        ]);
    }
}
