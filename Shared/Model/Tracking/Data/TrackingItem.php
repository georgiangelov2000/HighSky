<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Data;

use HighSky\Shared\Api\Tracking\Data\TrackingItemInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class TrackingItem extends AbstractSimpleObject implements TrackingItemInterface
{
    public function getProductId(): ?string
    {
        $productId = $this->_get(self::PRODUCT_ID);

        return $productId !== null ? (string) $productId : null;
    }

    public function setProductId(?string $productId): TrackingItemInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    public function getSku(): string
    {
        return (string) $this->_get(self::SKU);
    }

    public function setSku(string $sku): TrackingItemInterface
    {
        return $this->setData(self::SKU, $sku);
    }

    public function getName(): string
    {
        return (string) $this->_get(self::NAME);
    }

    public function setName(string $name): TrackingItemInterface
    {
        return $this->setData(self::NAME, $name);
    }

    public function getQty(): float
    {
        return (float) $this->_get(self::QTY);
    }

    public function setQty(float $qty): TrackingItemInterface
    {
        return $this->setData(self::QTY, $qty);
    }
}
