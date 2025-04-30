<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class CaptureRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return '/rest/deposit.do';
    }

    public function getData(): array
    {
        $data = parent::getData();
        unset($data['language']);

        return array_merge($data, [
            'orderId' => $this->getOrderId(),
            'amount' => $this->getAmountInteger(),
        ]);
    }

    /**
     * Creates a response object.
     *
     * @param array $data The response data.
     * @param int $statusCode The status code of the response.
     * @return CaptureResponse The created response object.
     */
    protected function createResponse(array $data, int $statusCode): CaptureResponse
    {
        return new CaptureResponse($this, $data, $statusCode);
    }
}
