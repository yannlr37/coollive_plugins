<?php

namespace Dreamteam\CoollivePlugins\Geocoding\Exceptions;

use Throwable;

abstract class BaseException extends \Exception
{
    /**
     * @var array
     */
    protected $context;

    public function __construct(string $message = "", array $context = [])
    {
        parent::__construct($message, 0, null);
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }
}