<?php

namespace Ampeco\OmnipayOtpGroup\Message;

interface ProvidesCardInfo
{
    public function getLast4(): string;
    public function getExpireMonth();
    public function getExpireYear();
    public function getType();
    public function getToken();
}
