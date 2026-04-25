<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Api\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingOrderResponseInterface;

interface OrderTrackingServiceInterface
{
    /**
     * Build the order tracking payload for an order identifier.
     *
     * @param string $orderId
     * @return TrackingOrderResponseInterface
     */
    public function execute(string $orderId): TrackingOrderResponseInterface;
}
