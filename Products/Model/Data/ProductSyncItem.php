<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Data;

use HighSky\Products\Api\Data\ProductSyncItemInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class ProductSyncItem extends AbstractSimpleObject implements ProductSyncItemInterface
{
    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return (int) $this->_get(self::ID);
    }

    /**
     * @inheritdoc
     */
    public function setId(int $id): ProductSyncItemInterface
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getSku(): string
    {
        return (string) $this->_get(self::SKU);
    }

    /**
     * @inheritdoc
     */
    public function setSku(string $sku): ProductSyncItemInterface
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return (string) $this->_get(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name): ProductSyncItemInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritdoc
     */
    public function getPrice(): ?float
    {
        $price = $this->_get(self::PRICE);

        return $price !== null ? (float) $price : null;
    }

    /**
     * @inheritdoc
     */
    public function setPrice(?float $price): ProductSyncItemInterface
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * @inheritdoc
     */
    public function getSpecialPrice(): ?float
    {
        $specialPrice = $this->_get(self::SPECIAL_PRICE);

        return $specialPrice !== null ? (float) $specialPrice : null;
    }

    /**
     * @inheritdoc
     */
    public function setSpecialPrice(?float $specialPrice): ProductSyncItemInterface
    {
        return $this->setData(self::SPECIAL_PRICE, $specialPrice);
    }

    /**
     * @inheritdoc
     */
    public function getSpecialFromDate(): ?string
    {
        $specialFromDate = $this->_get(self::SPECIAL_FROM_DATE);

        return $specialFromDate !== null ? (string) $specialFromDate : null;
    }

    /**
     * @inheritdoc
     */
    public function setSpecialFromDate(?string $specialFromDate): ProductSyncItemInterface
    {
        return $this->setData(self::SPECIAL_FROM_DATE, $specialFromDate);
    }

    /**
     * @inheritdoc
     */
    public function getSpecialToDate(): ?string
    {
        $specialToDate = $this->_get(self::SPECIAL_TO_DATE);

        return $specialToDate !== null ? (string) $specialToDate : null;
    }

    /**
     * @inheritdoc
     */
    public function setSpecialToDate(?string $specialToDate): ProductSyncItemInterface
    {
        return $this->setData(self::SPECIAL_TO_DATE, $specialToDate);
    }

    /**
     * @inheritdoc
     */
    public function getCost(): ?float
    {
        $cost = $this->_get(self::COST);

        return $cost !== null ? (float) $cost : null;
    }

    /**
     * @inheritdoc
     */
    public function setCost(?float $cost): ProductSyncItemInterface
    {
        return $this->setData(self::COST, $cost);
    }

    /**
     * @inheritdoc
     */
    public function getTaxClassId(): ?int
    {
        $taxClassId = $this->_get(self::TAX_CLASS_ID);

        return $taxClassId !== null ? (int) $taxClassId : null;
    }

    /**
     * @inheritdoc
     */
    public function setTaxClassId(?int $taxClassId): ProductSyncItemInterface
    {
        return $this->setData(self::TAX_CLASS_ID, $taxClassId);
    }

    /**
     * @inheritdoc
     */
    public function getCategoryNames(): array
    {
        return $this->_get(self::CATEGORY_NAMES) ?? [];
    }

    /**
     * @inheritdoc
     */
    public function setCategoryNames(array $categoryNames): ProductSyncItemInterface
    {
        return $this->setData(self::CATEGORY_NAMES, $categoryNames);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): string
    {
        return (string) $this->_get(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt(string $createdAt): ProductSyncItemInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): string
    {
        return (string) $this->_get(self::UPDATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedAt(string $updatedAt): ProductSyncItemInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): int
    {
        return (int) $this->_get(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): ProductSyncItemInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function getVisibility(): int
    {
        return (int) $this->_get(self::VISIBILITY);
    }

    /**
     * @inheritdoc
     */
    public function setVisibility(int $visibility): ProductSyncItemInterface
    {
        return $this->setData(self::VISIBILITY, $visibility);
    }

    /**
     * @inheritdoc
     */
    public function getImageUrl(): ?string
    {
        $imageUrl = $this->_get(self::IMAGE_URL);

        return $imageUrl !== null ? (string) $imageUrl : null;
    }

    /**
     * @inheritdoc
     */
    public function setImageUrl(?string $imageUrl): ProductSyncItemInterface
    {
        return $this->setData(self::IMAGE_URL, $imageUrl);
    }

    /**
     * @inheritdoc
     */
    public function getQty(): ?float
    {
        $qty = $this->_get(self::QTY);

        return $qty !== null ? (float) $qty : null;
    }

    /**
     * @inheritdoc
     */
    public function setQty(?float $qty): ProductSyncItemInterface
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * @inheritdoc
     */
    public function getIsInStock(): bool
    {
        return (bool) $this->_get(self::IS_IN_STOCK);
    }

    /**
     * @inheritdoc
     */
    public function setIsInStock(bool $isInStock): ProductSyncItemInterface
    {
        return $this->setData(self::IS_IN_STOCK, $isInStock);
    }

    /**
     * @inheritdoc
     */
    public function getManageStock(): bool
    {
        return (bool) $this->_get(self::MANAGE_STOCK);
    }

    /**
     * @inheritdoc
     */
    public function setManageStock(bool $manageStock): ProductSyncItemInterface
    {
        return $this->setData(self::MANAGE_STOCK, $manageStock);
    }

    /**
     * @inheritdoc
     */
    public function getUseConfigManageStock(): bool
    {
        return (bool) $this->_get(self::USE_CONFIG_MANAGE_STOCK);
    }

    /**
     * @inheritdoc
     */
    public function setUseConfigManageStock(bool $useConfigManageStock): ProductSyncItemInterface
    {
        return $this->setData(self::USE_CONFIG_MANAGE_STOCK, $useConfigManageStock);
    }

    /**
     * @inheritdoc
     */
    public function getBackorders(): int
    {
        return (int) $this->_get(self::BACKORDERS);
    }

    /**
     * @inheritdoc
     */
    public function setBackorders(int $backorders): ProductSyncItemInterface
    {
        return $this->setData(self::BACKORDERS, $backorders);
    }

    /**
     * @inheritdoc
     */
    public function getMinQty(): ?float
    {
        $minQty = $this->_get(self::MIN_QTY);

        return $minQty !== null ? (float) $minQty : null;
    }

    /**
     * @inheritdoc
     */
    public function setMinQty(?float $minQty): ProductSyncItemInterface
    {
        return $this->setData(self::MIN_QTY, $minQty);
    }

    /**
     * @inheritdoc
     */
    public function getMinSaleQty(): ?float
    {
        $minSaleQty = $this->_get(self::MIN_SALE_QTY);

        return $minSaleQty !== null ? (float) $minSaleQty : null;
    }

    /**
     * @inheritdoc
     */
    public function setMinSaleQty(?float $minSaleQty): ProductSyncItemInterface
    {
        return $this->setData(self::MIN_SALE_QTY, $minSaleQty);
    }

    /**
     * @inheritdoc
     */
    public function getMaxSaleQty(): ?float
    {
        $maxSaleQty = $this->_get(self::MAX_SALE_QTY);

        return $maxSaleQty !== null ? (float) $maxSaleQty : null;
    }

    /**
     * @inheritdoc
     */
    public function setMaxSaleQty(?float $maxSaleQty): ProductSyncItemInterface
    {
        return $this->setData(self::MAX_SALE_QTY, $maxSaleQty);
    }

    /**
     * @inheritdoc
     */
    public function getNotifyStockQty(): ?float
    {
        $notifyStockQty = $this->_get(self::NOTIFY_STOCK_QTY);

        return $notifyStockQty !== null ? (float) $notifyStockQty : null;
    }

    /**
     * @inheritdoc
     */
    public function setNotifyStockQty(?float $notifyStockQty): ProductSyncItemInterface
    {
        return $this->setData(self::NOTIFY_STOCK_QTY, $notifyStockQty);
    }

    /**
     * @inheritdoc
     */
    public function getEnableQtyIncrements(): bool
    {
        return (bool) $this->_get(self::ENABLE_QTY_INCREMENTS);
    }

    /**
     * @inheritdoc
     */
    public function setEnableQtyIncrements(bool $enableQtyIncrements): ProductSyncItemInterface
    {
        return $this->setData(self::ENABLE_QTY_INCREMENTS, $enableQtyIncrements);
    }

    /**
     * @inheritdoc
     */
    public function getQtyIncrements(): ?float
    {
        $qtyIncrements = $this->_get(self::QTY_INCREMENTS);

        return $qtyIncrements !== null ? (float) $qtyIncrements : null;
    }

    /**
     * @inheritdoc
     */
    public function setQtyIncrements(?float $qtyIncrements): ProductSyncItemInterface
    {
        return $this->setData(self::QTY_INCREMENTS, $qtyIncrements);
    }

    /**
     * @inheritdoc
     */
    public function getVariants(): array
    {
        return $this->_get(self::VARIANTS) ?? [];
    }

    /**
     * @inheritdoc
     */
    public function setVariants(array $variants): ProductSyncItemInterface
    {
        return $this->setData(self::VARIANTS, $variants);
    }
}
