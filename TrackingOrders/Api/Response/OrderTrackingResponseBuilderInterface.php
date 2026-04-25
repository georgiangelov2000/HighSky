<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Api\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingOrderResponseInterface;
use Magento\Sales\Api\Data\OrderInterface;

interface OrderTrackingResponseBuilderInterface
{
    /**
     * Build the public order tracking response payload.
     *
     * @param OrderInterface $order
     * @return TrackingOrderResponseInterface
     */
    public function build(OrderInterface $order): TrackingOrderResponseInterface;
}
