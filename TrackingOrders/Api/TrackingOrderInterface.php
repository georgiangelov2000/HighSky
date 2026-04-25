<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Api;

use HighSky\Shared\Api\Tracking\Data\TrackingOrderResponseInterface;

interface TrackingOrderInterface
{
    /**
     * REST entry point for order tracking payloads.
     *
     * @param string $orderId
     * @return TrackingOrderResponseInterface
     */
    public function execute(string $orderId): TrackingOrderResponseInterface;
}
