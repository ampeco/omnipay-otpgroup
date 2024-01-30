<?php

namespace Ampeco\OmnipayOtpGroup;

use Ampeco\OmnipayOtpGroup\Message\CaptureRequest;
use Ampeco\OmnipayOtpGroup\Message\CompleteOrderRequest;
use Ampeco\OmnipayOtpGroup\Message\AuthorizeRequest;
use Ampeco\OmnipayOtpGroup\Message\CreateCardRequest;
use Ampeco\OmnipayOtpGroup\Message\PurchaseRequest;
use Ampeco\OmnipayOtpGroup\Message\DeleteCardRequest;
use Ampeco\OmnipayOtpGroup\Message\RefundRequest;
use Ampeco\OmnipayOtpGroup\Message\ReverseRequest;
use Ampeco\OmnipayOtpGroup\Message\TransactionResultRequest;
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
    
    public function refund(array $options = [])
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    public function deleteCard(array $options = []): RequestInterface
    {
        return $this->createRequest(DeleteCardRequest::class, $options);
    }

    public function purchase(array $options = []): ?RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function authorize(array $options = []): ?RequestInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    public function capture(array $options = [])
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    protected function createRequest($class, array $parameters)
    {
        return parent::createRequest($class, $parameters)->setGateway($this);
    }

    public function completeOrder(array $options = [])
    {
        return $this->createRequest(CompleteOrderRequest::class, $options);
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
        if ($this->getUseSignature()) {
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

    public function getCreateCardCurrency()
    {
        return $this->getParameter('createCardCurrency');
    }

    public function getCreateCardAmount(): int
    {
        return 1;
    }
}
