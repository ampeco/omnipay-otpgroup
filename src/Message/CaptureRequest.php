<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class CaptureRequest extends AbstractRequest
{
    public function setDepositType(int $depositType): void
    {
        $this->setParameter('depositType', $depositType);
    }

    public function getDepositType(): int
    {
        return $this->getParameter('depositType');
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return '/rest/deposit.do';
    }

    public function getData(): array
    {
        return array_merge(parent::getData(), [
            'orderId' => $this->getOrderId(),
            'amount' => $this->getAmountInteger(),
            'depositType' => $this->getDepositType(),
        ]);
    }

    /**
     * Creates a response object.
     *
     * @param array $data The response data.
     * @param int $statusCode The status code of the response.
     * @return Response The created response object.
     */
    protected function createResponse(array $data, int $statusCode): Response
    {
        return new Response($this, $data, $statusCode);
    }
}
