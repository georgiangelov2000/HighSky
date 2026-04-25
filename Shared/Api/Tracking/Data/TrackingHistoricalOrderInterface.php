<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Data;

interface TrackingHistoricalOrderInterface
{
    public const ORDER_ID = 'order_id';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const ITEMS = 'items';

    /**
     * @return string
     */
    public function getOrderId(): string;

    /**
     * @param string $orderId
     * @return $this
     */
    public function setOrderId(string $orderId): self;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * @return \HighSky\Shared\Api\Tracking\Data\TrackingItemInterface[]
     */
    public function getItems();

    /**
     * @param \HighSky\Shared\Api\Tracking\Data\TrackingItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items): self;
}
