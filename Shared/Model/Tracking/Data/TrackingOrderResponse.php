<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Data;

use HighSky\Shared\Api\Tracking\Data\TrackingOrderResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class TrackingOrderResponse extends AbstractSimpleObject implements TrackingOrderResponseInterface
{
    public function getOrderId(): string
    {
        return (string) $this->_get(self::ORDER_ID);
    }

    public function setOrderId(string $orderId): TrackingOrderResponseInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function getStatus(): string
    {
        return (string) $this->_get(self::STATUS);
    }

    public function setStatus(string $status): TrackingOrderResponseInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getCreatedAt(): string
    {
        return (string) $this->_get(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt): TrackingOrderResponseInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUserId(): ?string
    {
        $userId = $this->_get(self::USER_ID);

        return $userId !== null ? (string) $userId : null;
    }

    public function setUserId(?string $userId): TrackingOrderResponseInterface
    {
        return $this->setData(self::USER_ID, $userId);
    }

    public function getUserName(): ?string
    {
        $userName = $this->_get(self::USER_NAME);

        return $userName !== null ? (string) $userName : null;
    }

    public function setUserName(?string $userName): TrackingOrderResponseInterface
    {
        return $this->setData(self::USER_NAME, $userName);
    }

    public function getUserEmail(): ?string
    {
        $userEmail = $this->_get(self::USER_EMAIL);

        return $userEmail !== null ? (string) $userEmail : null;
    }

    public function setUserEmail(?string $userEmail): TrackingOrderResponseInterface
    {
        return $this->setData(self::USER_EMAIL, $userEmail);
    }

    public function getUserTelephone(): ?string
    {
        $userTelephone = $this->_get(self::USER_TELEPHONE);

        return $userTelephone !== null ? (string) $userTelephone : null;
    }

    public function setUserTelephone(?string $userTelephone): TrackingOrderResponseInterface
    {
        return $this->setData(self::USER_TELEPHONE, $userTelephone);
    }

    public function getGrandTotal(): float
    {
        return (float) $this->_get(self::GRAND_TOTAL);
    }

    public function setGrandTotal(float $grandTotal): TrackingOrderResponseInterface
    {
        return $this->setData(self::GRAND_TOTAL, $grandTotal);
    }

    public function getCurrencyCode(): string
    {
        return (string) $this->_get(self::CURRENCY_CODE);
    }

    public function setCurrencyCode(string $currencyCode): TrackingOrderResponseInterface
    {
        return $this->setData(self::CURRENCY_CODE, $currencyCode);
    }

    public function getItems()
    {
        return $this->_get(self::ITEMS) ?? [];
    }

    public function setItems(array $items): TrackingOrderResponseInterface
    {
        return $this->setData(self::ITEMS, $items);
    }
}
