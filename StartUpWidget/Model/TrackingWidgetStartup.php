<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\Model;

use HighSky\Shared\Api\Tracking\Data\TrackingWidgetStartupResponseInterface;
use HighSky\Shared\Model\Tracking\AbstractTrackingEndpoint;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;
use HighSky\StartUpWidget\Api\Service\TrackingWidgetStartupServiceInterface;
use HighSky\StartUpWidget\Api\TrackingWidgetStartupInterface;

class TrackingWidgetStartup extends AbstractTrackingEndpoint implements TrackingWidgetStartupInterface
{
    public function __construct(
        \HighSky\Shared\Model\Tracking\Service\TrackingAvailabilityValidator $trackingAvailabilityValidator,
        \HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface $authHeaderValidator,
        \HighSky\Shared\Api\Tracking\Validator\TrackingRequestValidatorInterface $trackingRequestValidator,
        TrackingApiExceptionFactory $trackingApiExceptionFactory,
        private readonly TrackingWidgetStartupServiceInterface $trackingWidgetStartupService
    ) {
        parent::__construct(
            $trackingAvailabilityValidator,
            $authHeaderValidator,
            $trackingRequestValidator,
            $trackingApiExceptionFactory
        );
    }

    public function execute(): TrackingWidgetStartupResponseInterface
    {
        return $this->executeWithErrorHandling(function (): array {
            $this->trackingAvailabilityValidator->validate();
            $this->authHeaderValidator->validate();

            return ['response' => $this->trackingWidgetStartupService->execute()];
        })['response'];
    }
}
