<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Data;

interface TrackingOrderResponseInterface
{
    public const ORDER_ID = 'order_id';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const USER_ID = 'user_id';
    public const USER_NAME = 'user_name';
    public const USER_EMAIL = 'user_email';
    public const USER_TELEPHONE = 'user_telephone';
    public const GRAND_TOTAL = 'grand_total';
    public const CURRENCY_CODE = 'currency_code';
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
     * @return string|null
     */
    public function getUserId(): ?string;

    /**
     * @param string|null $userId
     * @return $this
     */
    public function setUserId(?string $userId): self;

    /**
     * @return string|null
     */
    public function getUserName(): ?string;

    /**
     * @param string|null $userName
     * @return $this
     */
    public function setUserName(?string $userName): self;

    /**
     * @return string|null
     */
    public function getUserEmail(): ?string;

    /**
     * @param string|null $userEmail
     * @return $this
     */
    public function setUserEmail(?string $userEmail): self;

    /**
     * @return string|null
     */
    public function getUserTelephone(): ?string;

    /**
     * @param string|null $userTelephone
     * @return $this
     */
    public function setUserTelephone(?string $userTelephone): self;

    /**
     * @return float
     */
    public function getGrandTotal(): float;

    /**
     * @param float $grandTotal
     * @return $this
     */
    public function setGrandTotal(float $grandTotal): self;

    /**
     * @return string
     */
    public function getCurrencyCode(): string;

    /**
     * @param string $currencyCode
     * @return $this
     */
    public function setCurrencyCode(string $currencyCode): self;

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
