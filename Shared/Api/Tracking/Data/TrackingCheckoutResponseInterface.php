<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Data;

interface TrackingCheckoutResponseInterface
{
    public const SESSION_ID = 'session_id';
    public const QUOTE_ID = 'quote_id';
    public const IS_ACTIVE = 'is_active';
    public const CUSTOMER_ID = 'customer_id';
    public const CUSTOMER_EMAIL = 'customer_email';
    public const ITEMS_COUNT = 'items_count';
    public const ITEMS_QTY = 'items_qty';
    public const GRAND_TOTAL = 'grand_total';
    public const CURRENCY_CODE = 'currency_code';
    public const ITEMS = 'items';

    /**
     * @return string
     */
    public function getSessionId(): string;

    /**
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId(string $sessionId): self;

    /**
     * @return string
     */
    public function getQuoteId(): string;

    /**
     * @param string $quoteId
     * @return $this
     */
    public function setQuoteId(string $quoteId): self;

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): self;

    /**
     * @return string|null
     */
    public function getCustomerId(): ?string;

    /**
     * @param string|null $customerId
     * @return $this
     */
    public function setCustomerId(?string $customerId): self;

    /**
     * @return string|null
     */
    public function getCustomerEmail(): ?string;

    /**
     * @param string|null $customerEmail
     * @return $this
     */
    public function setCustomerEmail(?string $customerEmail): self;

    /**
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * @param int $itemsCount
     * @return $this
     */
    public function setItemsCount(int $itemsCount): self;

    /**
     * @return float
     */
    public function getItemsQty(): float;

    /**
     * @param float $itemsQty
     * @return $this
     */
    public function setItemsQty(float $itemsQty): self;

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
