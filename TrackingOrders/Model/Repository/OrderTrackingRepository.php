<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Model\Repository;

use HighSky\TrackingOrders\Api\Service\OrderTrackingRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class OrderTrackingRepository implements OrderTrackingRepositoryInterface
{
    public function __construct(
        private readonly CollectionFactory $orderCollectionFactory
    ) {}

    public function getByOrderId(string $orderId): ?OrderInterface
    {
        $collection = $this->orderCollectionFactory->create();
        $collection->addFieldToSelect([
            'entity_id',
            'increment_id',
            'status',
            'created_at',
            'customer_id',
            'customer_firstname',
            'customer_lastname',
            'customer_email',
            'grand_total',
            'order_currency_code',
        ]);
        $collection->addFieldToFilter('increment_id', $orderId);
        $collection->setPageSize(1);
        $collection->setCurPage(1);

        $order = $collection->getFirstItem();
        if (!$order->getEntityId()) {
            return null;
        }

        return $order;
    }
}
