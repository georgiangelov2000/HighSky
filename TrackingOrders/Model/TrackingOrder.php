<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Model;

use HighSky\Shared\Api\Tracking\Data\TrackingOrderResponseInterface;
use HighSky\Shared\Model\Tracking\AbstractTrackingEndpoint;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;
use HighSky\TrackingOrders\Api\Service\OrderTrackingServiceInterface;
use HighSky\TrackingOrders\Api\TrackingOrderInterface;

class TrackingOrder extends AbstractTrackingEndpoint implements TrackingOrderInterface
{
    public function __construct(
        \HighSky\Shared\Model\Tracking\Service\TrackingAvailabilityValidator $trackingAvailabilityValidator,
        \HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface $authHeaderValidator,
        \HighSky\Shared\Api\Tracking\Validator\TrackingRequestValidatorInterface $trackingRequestValidator,
        TrackingApiExceptionFactory $trackingApiExceptionFactory,
        private readonly OrderTrackingServiceInterface $orderTrackingService
    ) {
        parent::__construct(
            $trackingAvailabilityValidator,
            $authHeaderValidator,
            $trackingRequestValidator,
            $trackingApiExceptionFactory
        );
    }

    public function execute(string $orderId): TrackingOrderResponseInterface
    {
        return $this->executeWithErrorHandling(function () use ($orderId): array {
            $this->trackingAvailabilityValidator->validate();
            $this->authHeaderValidator->validate();
            $validatedOrderId = $this->trackingRequestValidator->validateOrderId($orderId);

            return ['response' => $this->orderTrackingService->execute($validatedOrderId)];
        })['response'];
    }
}
