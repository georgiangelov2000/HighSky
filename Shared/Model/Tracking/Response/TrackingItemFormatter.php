<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingItemInterface;
use HighSky\Shared\Model\Tracking\Data\TrackingItemFactory;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class TrackingItemFormatter
{
    public function __construct(
        private readonly TrackingItemFactory $trackingItemFactory
    ) {}

    /**
     * @param OrderItemInterface $item
     * @return TrackingItemInterface
     */
    public function formatOrderItem(OrderItemInterface $item): TrackingItemInterface
    {
        $trackingItem = $this->trackingItemFactory->create();
        $trackingItem->setProductId($item->getProductId() !== null ? (string) $item->getProductId() : null);
        $trackingItem->setSku((string) $item->getSku());
        $trackingItem->setName((string) $item->getName());
        $trackingItem->setQty((float) $item->getQtyOrdered());

        return $trackingItem;
    }

    /**
     * @param CartItemInterface $item
     * @return TrackingItemInterface
     */
    public function formatQuoteItem(CartItemInterface $item): TrackingItemInterface
    {
        $trackingItem = $this->trackingItemFactory->create();
        $trackingItem->setProductId($item->getProductId() !== null ? (string) $item->getProductId() : null);
        $trackingItem->setSku((string) $item->getSku());
        $trackingItem->setName((string) $item->getName());
        $trackingItem->setQty((float) $item->getQty());

        return $trackingItem;
    }
}
