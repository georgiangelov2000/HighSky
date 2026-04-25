<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Service;

interface AuthHeaderValidatorInterface
{
    /**
     * Validate the tracking auth header or throw a web API exception.
     */
    public function validate(): void;
}
