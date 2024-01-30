<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class RefundRequest extends AbstractRequest
{
    /**
     * @return mixed
     */
    public function getEndpoint(): string
    {
        return '/rest/refund.do';
    }

    public function getData(): array
    {
        return array_merge(parent::getData(), [
            'orderId' => $this->getOrderId(),
            'amount' => $this->getAmountInteger(),
        ]);
    }

    /**
     * @param array $data
     * @param int $statusCode
     * @return mixed
     */
    protected function createResponse(array $data, int $statusCode): Response
    {
        return new Response($this, $data, $statusCode);
    }
}
