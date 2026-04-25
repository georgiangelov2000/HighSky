<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Data;

use HighSky\Shared\Api\Tracking\Data\TrackingHistoricalOrderInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class TrackingHistoricalOrder extends AbstractSimpleObject implements TrackingHistoricalOrderInterface
{
    public function getOrderId(): string
    {
        return (string) $this->_get(self::ORDER_ID);
    }

    public function setOrderId(string $orderId): TrackingHistoricalOrderInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function getStatus(): string
    {
        return (string) $this->_get(self::STATUS);
    }

    public function setStatus(string $status): TrackingHistoricalOrderInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getCreatedAt(): string
    {
        return (string) $this->_get(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt): TrackingHistoricalOrderInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getItems()
    {
        return $this->_get(self::ITEMS) ?? [];
    }

    public function setItems(array $items): TrackingHistoricalOrderInterface
    {
        return $this->setData(self::ITEMS, $items);
    }
}
