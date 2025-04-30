<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return '/rest/register.do';
    }

    public function getData(): array
    {
        return array_merge(parent::getData(), [
            'orderNumber' => $this->getOrderNumber(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'returnUrl' => $this->getReturnUrl(),
            'description' => $this->getDescription(),
            'language' => $this->getLanguage(),
            'clientId' => $this->getClientId(),
            'bindingId' => $this->getBindingId(),
        ]);
    }

    /**
     * @param array $data
     * @param int $statusCode
     * @return mixed
     */
    protected function createResponse(array $data, int $statusCode): Response
    {
        return new PurchaseResponse($this, $data, $statusCode);
    }
}
