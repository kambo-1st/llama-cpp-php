<?php

namespace Kambo\LLamaCpp\Events;

use Symfony\Contracts\EventDispatcher\Event;

class TokenGeneratedEvent extends Event
{
    public const NAME = 'token.generated';

    public function __construct(
        protected string $token,
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
