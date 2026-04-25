<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Api\Service;

use Magento\Sales\Api\Data\OrderInterface;

interface OrderTrackingRepositoryInterface
{
    /**
     * Resolve a Magento order by external tracking order id.
     *
     * @param string $orderId
     * @return OrderInterface|null
     */
    public function getByOrderId(string $orderId): ?OrderInterface;
}
