<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class CompleteOrderRequest extends AbstractRequest
{

    /**
     * @return mixed
     */
    public function getEndpoint(): string
    {
        return '/rest/paymentOrderBinding.do';
    }

    public function getData(): array
    {
        return array_merge(parent::getData(), [
            'mdOrder' => $this->getOrderId(),
            'bindingId' => $this->getBindingId(),
            //TODO: Look at https://uat.dskbank.bg/sandbox/integration/api/rest/rest.html#payment-for-order for possible "tii" values
            //probably will be changed when 3DS(SCA) is need to be implemented
            'tii' => 'U', // penalty mode - no CVV|CVC, no 3DS
        ]);
    }

    /**
     * @param array $data
     * @param int $statusCode
     */
    function createResponse(array $data, int $statusCode): Response
    {
        $data['orderId'] = $this->getOrderId();
        return new CompleteOrderResponse($this, $data, $statusCode);
    }

}
