<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Service;

use HighSky\Shared\Model\Config\GeneralConfig;
use HighSky\Shared\Model\Config\Tracking\TrackingConfig;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;

class TrackingAvailabilityValidator
{
    public function __construct(
        private readonly GeneralConfig $generalConfig,
        private readonly TrackingConfig $trackingConfig,
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    public function validate(): void
    {
        if (!$this->generalConfig->isModuleEnabled()) {
            throw $this->trackingApiExceptionFactory->forbidden(
                'The HighSky module is disabled.'
            );
        }

        if (!$this->trackingConfig->isTrackingEnabled()) {
            throw $this->trackingApiExceptionFactory->forbidden(
                'The HighSky tracking API is disabled.'
            );
        }
    }
}
