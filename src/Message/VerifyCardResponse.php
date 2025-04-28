<?php

namespace Ampeco\OmnipayOtpGroup\Message;

class VerifyCardResponse extends TransactionResultResponse
{
    public function isSuccessful(): bool
    {
        // orderStatus values:
        // 0 - order was registered but not paid;
        // 1 - order was authorized only and wasn't captured yet (for two-phase payments);
        // 2 - order was authorized and captured;
        // 3 - authorization canceled;
        // 4 - transaction was refunded;
        // 5 - access control server of the issuing bank initiated authorization procedure;
        // 6 - authorization declined;
        // 7 - pending order payment;
        // 8 - intermediate completion for multiple partial completion.
        return parent::isSuccessful()
            && array_key_exists('orderStatus', $this->data)
            && (int) $this->data['orderStatus'] === 3 // we expect to have canceled authorization when tokenizing cards
            && array_key_exists('bindingInfo', $this->data);
    }

    public function getLast4(): string
    {
        return substr($this->data['cardAuthInfo']['pan'], -4);
    }

    public function getExpireMonth(): int
    {
        return (int) substr($this->data['cardAuthInfo']['expiration'], -2);
    }

    public function getExpireYear(): int
    {
        return (int) substr($this->data['cardAuthInfo']['expiration'], 0, 4);
    }

    public function getType(): string
    {
        return $this->data['cardAuthInfo']['paymentSystem'] ?? 'unknown';
    }

    public function getToken(): ?string
    {
        return $this->data['bindingInfo']['bindingId'] ?? null;
    }

    public function getCardReference(): ?string
    {
        return $this->isSuccessful() ? $this->getToken() : null;
    }

    public function getPaymentMethod(): object
    {
        $result = new \stdClass();

        $result->imageUrl = '';
        $result->last4 = $this->getLast4();
        $result->cardType = $this->getType();

        $result->expirationMonth = $this->getExpireMonth();
        $result->expirationYear = $this->getExpireYear();

        return $result;
    }
}