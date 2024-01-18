<?php

namespace Ampeco\OmnipayOtpGroup;

use Ampeco\OmnipayOtpGroup\Messages\CaptureRequest;
use Ampeco\OmnipayOtpGroup\Messages\CompleteOrderRequest;
use Ampeco\OmnipayOtpGroup\Messages\CreateAuthorizeRequest;
use Ampeco\OmnipayOtpGroup\Messages\CreateAuthorizeResponse;
use Ampeco\OmnipayOtpGroup\Messages\CreateCardRequest;
use Ampeco\OmnipayOtpGroup\Messages\CreatePurchaseRequest;
use Ampeco\OmnipayOtpGroup\Messages\CreatePurchaseResponse;
use Ampeco\OmnipayOtpGroup\Messages\DeleteCardRequest;
use Ampeco\OmnipayOtpGroup\Messages\ReverseRequest;
use Ampeco\OmnipayOtpGroup\Messages\TransactionResultRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

class Gateway extends AbstractGateway
{

    use CommonParameters;

    /**
     * @return string
     */
    public function getName()
    {
        return 'OtpGroup';
    }

    public function createCard(array $options = [])
    {
        return $this->createRequest(CreateCardRequest::class, $options);
    }

    public function fetchTransaction(array $options = []): RequestInterface
    {
        return $this->createRequest(TransactionResultRequest::class, $options);
    }

    public function void(array $options = [])
    {
        return $this->createRequest(ReverseRequest::class, $options);
    }

    public function deleteCard(array $options = array()): RequestInterface
    {
        return $this->createRequest(DeleteCardRequest::class, $options);
    }

    public function purchase(array $options = array()): RequestInterface|false
    {
        /** @var CreatePurchaseResponse $response */
        $response = $this->createRequest(CreatePurchaseRequest::class, $options)->send(); //register payment

        if($response->isSuccessful()) {
            return $this->createRequest(CompleteOrderRequest::class, [ //confirm payment
                'orderId' => $response->getOrderId(),
                'bindingId' => $options['bindingId'],
                'language' => $options['language'],
            ]);
        }
        return false;
    }
    public function authorize(array $options = array()): RequestInterface
    {
        /** @var CreateAuthorizeResponse $response */
        $response = $this->createRequest(CreateAuthorizeRequest::class, $options)->send();
        if($response->isSuccessful()) {
            return $this->createRequest(CompleteOrderRequest::class, [ //confirm payment
                'orderId' => $response->getOrderId(),
                'bindingId' => $options['bindingId'],
                'language' => $options['language'],
            ]);
        }
        return false;
    }

    public function capture(array $options = [])
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    protected function createRequest($class, array $parameters)
    {
        return parent::createRequest($class, $parameters)->setGateway($this);
    }

    public function signHeaders(string $body)
    {
        if ($signSettings = $this->signSettings()) {
            [$privateKey, $password] = $signSettings;
            $hash = hash('sha256', $body, true);
            $signature = $this->createSignature($privateKey, $password, $hash);
            return [
                'X-Hash' => base64_encode($hash),
                'X-Signature' => base64_encode($signature),
            ];
        }
        return [];
    }

    private function signSettings()
    {
        if($this->getUseSignature()){
            $privateKey = $this->getPrivateKey();
            $password = $this->getPrivateKeyPassword();
            if ($privateKey && $password) {
                return [$privateKey, $password];
            }
        }
        return null;
    }

    private function createSignature(string $privateKey, string $password, string $hash): string
    {
        $pKey = openssl_pkey_get_private($privateKey, $password);
        openssl_sign($hash, $signature, $pKey, OPENSSL_ALGO_SHA256);
        return $signature;
    }

    public function setUseSignature($value)
    {
        $this->setParameter('use_signature', $value);
    }

    public function getUseSignature()
    {
        return $this->getParameter('use_signature');
    }

    public function setPrivateKey($value)
    {
        $this->setParameter('private_key', $value);
    }

    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKeyPassword($value)
    {
        $this->setParameter('private_key_password', $value);
    }

    public function getPrivateKeyPassword()
    {
        return $this->getParameter('private_key_password');
    }
}
