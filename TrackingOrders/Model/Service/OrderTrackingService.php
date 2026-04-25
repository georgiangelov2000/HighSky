<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Model\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingOrderResponseInterface;
use HighSky\TrackingOrders\Api\Response\OrderTrackingResponseBuilderInterface;
use HighSky\TrackingOrders\Api\Service\OrderTrackingRepositoryInterface;
use HighSky\TrackingOrders\Api\Service\OrderTrackingServiceInterface;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;

class OrderTrackingService implements OrderTrackingServiceInterface
{
    public function __construct(
        private readonly OrderTrackingRepositoryInterface $orderTrackingRepository,
        private readonly OrderTrackingResponseBuilderInterface $orderTrackingResponseBuilder,
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    public function execute(string $orderId): TrackingOrderResponseInterface
    {
        $order = $this->orderTrackingRepository->getByOrderId($orderId);
        if ($order === null) {
            throw $this->trackingApiExceptionFactory->notFound(
                sprintf('Order "%s" was not found.', $orderId)
            );
        }

        return $this->orderTrackingResponseBuilder->build($order);
    }
}
