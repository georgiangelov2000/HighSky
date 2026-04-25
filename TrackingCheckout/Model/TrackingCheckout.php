<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Model;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;
use HighSky\Shared\Model\Tracking\AbstractTrackingEndpoint;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;
use HighSky\TrackingCheckout\Api\Service\CheckoutTrackingServiceInterface;
use HighSky\TrackingCheckout\Api\TrackingCheckoutInterface;

class TrackingCheckout extends AbstractTrackingEndpoint implements TrackingCheckoutInterface
{
    public function __construct(
        \HighSky\Shared\Model\Tracking\Service\TrackingAvailabilityValidator $trackingAvailabilityValidator,
        \HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface $authHeaderValidator,
        \HighSky\Shared\Api\Tracking\Validator\TrackingRequestValidatorInterface $trackingRequestValidator,
        \HighSky\Shared\Model\Security\RequestRateLimiter $requestRateLimiter,
        TrackingApiExceptionFactory $trackingApiExceptionFactory,
        private readonly CheckoutTrackingServiceInterface $checkoutTrackingService
    ) {
        parent::__construct(
            $trackingAvailabilityValidator,
            $authHeaderValidator,
            $trackingRequestValidator,
            $requestRateLimiter,
            $trackingApiExceptionFactory
        );
    }

    public function execute(string $sessionId): TrackingCheckoutResponseInterface
    {
        return $this->executeProtected('tracking_checkout', function () use ($sessionId): TrackingCheckoutResponseInterface {
            $validatedSessionId = $this->trackingRequestValidator->validateSessionId($sessionId);

            return $this->checkoutTrackingService->execute($validatedSessionId);
        });
    }
}
