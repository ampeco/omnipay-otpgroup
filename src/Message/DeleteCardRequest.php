<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class DeleteCardRequest extends AbstractRequest
{

    /**
     * @return mixed
     */
    public function getEndpoint(): string
    {
        return '/rest/unBindCard.do';
    }

    public function getData(): array
    {
        $data = parent::getData();
        unset($data['language']);
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
        return new Response($this, $data, $statusCode);
    }
}
