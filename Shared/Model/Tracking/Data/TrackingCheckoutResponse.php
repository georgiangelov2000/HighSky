<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Data;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class TrackingCheckoutResponse extends AbstractSimpleObject implements TrackingCheckoutResponseInterface
{
    public function getSessionId(): string
    {
        return (string) $this->_get(self::SESSION_ID);
    }

    public function setSessionId(string $sessionId): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::SESSION_ID, $sessionId);
    }

    public function getQuoteId(): string
    {
        return (string) $this->_get(self::QUOTE_ID);
    }

    public function setQuoteId(string $quoteId): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    public function getIsActive(): bool
    {
        return (bool) $this->_get(self::IS_ACTIVE);
    }

    public function setIsActive(bool $isActive): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function getCustomerId(): ?string
    {
        $customerId = $this->_get(self::CUSTOMER_ID);

        return $customerId !== null ? (string) $customerId : null;
    }

    public function setCustomerId(?string $customerId): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getCustomerEmail(): ?string
    {
        $customerEmail = $this->_get(self::CUSTOMER_EMAIL);

        return $customerEmail !== null ? (string) $customerEmail : null;
    }

    public function setCustomerEmail(?string $customerEmail): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    public function getItemsCount(): int
    {
        return (int) $this->_get(self::ITEMS_COUNT);
    }

    public function setItemsCount(int $itemsCount): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::ITEMS_COUNT, $itemsCount);
    }

    public function getItemsQty(): float
    {
        return (float) $this->_get(self::ITEMS_QTY);
    }

    public function setItemsQty(float $itemsQty): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::ITEMS_QTY, $itemsQty);
    }

    public function getGrandTotal(): float
    {
        return (float) $this->_get(self::GRAND_TOTAL);
    }

    public function setGrandTotal(float $grandTotal): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::GRAND_TOTAL, $grandTotal);
    }

    public function getCurrencyCode(): string
    {
        return (string) $this->_get(self::CURRENCY_CODE);
    }

    public function setCurrencyCode(string $currencyCode): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::CURRENCY_CODE, $currencyCode);
    }

    public function getItems()
    {
        return $this->_get(self::ITEMS) ?? [];
    }

    public function setItems(array $items): TrackingCheckoutResponseInterface
    {
        return $this->setData(self::ITEMS, $items);
    }
}
