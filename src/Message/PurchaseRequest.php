<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class PurchaseRequest extends CreateCardRequest
{

    public function getData(): array
    {
        $data = parent::getData();
        $data['bindingId'] = $this->getBindingId();
        return $data;
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
