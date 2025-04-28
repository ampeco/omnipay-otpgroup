<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class CreateCardRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return '/rest/registerPreAuth.do';
    }

    public function getData(): array
    {
        return array_merge(parent::getData(), [
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'orderNumber' => $this->getOrderNumber(),
            'returnUrl' => $this->getReturnUrl(),
            'clientId' => $this->getClientId(),
            'description' => $this->getDescription(),
            'features' => $this->getFeatures(),
        ]);
    }

    protected function createResponse(array $data, int $statusCode): Response
    {
        return new CreateCardResponse($this, $data, $statusCode);
    }
}
