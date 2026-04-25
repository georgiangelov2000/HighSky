<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Model;

use HighSky\Shared\Api\Tracking\Data\TrackingUserResponseInterface;
use HighSky\Shared\Model\Tracking\AbstractTrackingEndpoint;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;
use HighSky\TrackingUsers\Api\Service\UserTrackingServiceInterface;
use HighSky\TrackingUsers\Api\TrackingUserInterface;

class TrackingUser extends AbstractTrackingEndpoint implements TrackingUserInterface
{
    public function __construct(
        \HighSky\Shared\Model\Tracking\Service\TrackingAvailabilityValidator $trackingAvailabilityValidator,
        \HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface $authHeaderValidator,
        \HighSky\Shared\Api\Tracking\Validator\TrackingRequestValidatorInterface $trackingRequestValidator,
        \HighSky\Shared\Model\Security\RequestRateLimiter $requestRateLimiter,
        TrackingApiExceptionFactory $trackingApiExceptionFactory,
        private readonly UserTrackingServiceInterface $userTrackingService
    ) {
        parent::__construct(
            $trackingAvailabilityValidator,
            $authHeaderValidator,
            $trackingRequestValidator,
            $requestRateLimiter,
            $trackingApiExceptionFactory
        );
    }

    public function execute(string $userId): TrackingUserResponseInterface
    {
        return $this->executeWithErrorHandling(function () use ($userId): array {
            $this->trackingAvailabilityValidator->validate();
            $this->enforceRateLimit('tracking_users');
            $this->authHeaderValidator->validate();
            $validatedUserId = $this->trackingRequestValidator->validateUserId($userId);

            return ['response' => $this->userTrackingService->execute($validatedUserId)];
        })['response'];
    }
}
