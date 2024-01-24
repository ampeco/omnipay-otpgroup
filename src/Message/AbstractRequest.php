<?php

namespace Ampeco\OmnipayOtpGroup\Message;


use Ampeco\OmnipayOtpGroup\Gateway;
use Ampeco\OmnipayOtpGroup\CommonParameters;
use Omnipay\Common\Message\AbstractRequest as OmniPayAbstractRequest;

abstract class AbstractRequest extends OmniPayAbstractRequest
{
    use CommonParameters;
    protected const HTTP_METHOD = 'POST';

    private Gateway $gateway;

    abstract public function getEndpoint(): string;

    abstract protected function createResponse(array $data, int $statusCode): Response;

    public function setGateway(Gateway $gateway): static
    {
        $this->gateway = $gateway;
        return $this;
    }

    private function getHeaders(string $body)
    {
        return array_merge($this->gateway->signHeaders($body), [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'Content-Length' => (string)strlen($body),
        ]);
    }

    public function sendData($data): Response
    {
        $body = http_build_query($data);
        $response = $this->httpClient->request(
            self::HTTP_METHOD,
            $this->gateway->getBaseUrl() . $this->getEndpoint(),
            $this->getHeaders($body),
            $body
        );
        return $this->createResponse(
            json_decode($response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR),
            $response->getStatusCode(),
        );
    }

    public function getData(): array
    {
        return [
            'userName' => $this->getParameter('username'),
            'password' => $this->getParameter('password'),
            'language' => $this->getParameter('language'),
        ];
    }

}
