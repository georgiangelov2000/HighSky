<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Data;

interface ProductSyncItemInterface
{
    public const ID = 'id';
    public const SKU = 'sku';
    public const NAME = 'name';
    public const PRICE = 'price';
    public const SPECIAL_PRICE = 'special_price';
    public const SPECIAL_FROM_DATE = 'special_from_date';
    public const SPECIAL_TO_DATE = 'special_to_date';
    public const COST = 'cost';
    public const TAX_CLASS_ID = 'tax_class_id';
    public const CATEGORY_IDS = 'category_ids';
    public const CATEGORY_NAMES = 'category_names';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const STATUS = 'status';
    public const VISIBILITY = 'visibility';
    public const IMAGE_URL = 'image_url';
    public const QTY = 'qty';
    public const IS_IN_STOCK = 'is_in_stock';
    public const MANAGE_STOCK = 'manage_stock';
    public const USE_CONFIG_MANAGE_STOCK = 'use_config_manage_stock';
    public const BACKORDERS = 'backorders';
    public const MIN_QTY = 'min_qty';
    public const MIN_SALE_QTY = 'min_sale_qty';
    public const MAX_SALE_QTY = 'max_sale_qty';
    public const NOTIFY_STOCK_QTY = 'notify_stock_qty';
    public const ENABLE_QTY_INCREMENTS = 'enable_qty_increments';
    public const QTY_INCREMENTS = 'qty_increments';

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * @return float|null
     */
    public function getPrice(): ?float;

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice(?float $price): self;

    /**
     * @return float|null
     */
    public function getSpecialPrice(): ?float;

    /**
     * @param float|null $specialPrice
     * @return $this
     */
    public function setSpecialPrice(?float $specialPrice): self;

    /**
     * @return string|null
     */
    public function getSpecialFromDate(): ?string;

    /**
     * @param string|null $specialFromDate
     * @return $this
     */
    public function setSpecialFromDate(?string $specialFromDate): self;

    /**
     * @return string|null
     */
    public function getSpecialToDate(): ?string;

    /**
     * @param string|null $specialToDate
     * @return $this
     */
    public function setSpecialToDate(?string $specialToDate): self;

    /**
     * @return float|null
     */
    public function getCost(): ?float;

    /**
     * @param float|null $cost
     * @return $this
     */
    public function setCost(?float $cost): self;

    /**
     * @return int|null
     */
    public function getTaxClassId(): ?int;

    /**
     * @param int|null $taxClassId
     * @return $this
     */
    public function setTaxClassId(?int $taxClassId): self;

    /**
     * @return int[]
     */
    public function getCategoryIds(): array;

    /**
     * @param int[] $categoryIds
     * @return $this
     */
    public function setCategoryIds(array $categoryIds): self;

    /**
     * @return string[]
     */
    public function getCategoryNames(): array;

    /**
     * @param string[] $categoryNames
     * @return $this
     */
    public function setCategoryNames(array $categoryNames): self;

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
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): self;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self;

    /**
     * @return int
     */
    public function getVisibility(): int;

    /**
     * @param int $visibility
     * @return $this
     */
    public function setVisibility(int $visibility): self;

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string;

    /**
     * @param string|null $imageUrl
     * @return $this
     */
    public function setImageUrl(?string $imageUrl): self;

    /**
     * @return float|null
     */
    public function getQty(): ?float;

    /**
     * @param float|null $qty
     * @return $this
     */
    public function setQty(?float $qty): self;

    /**
     * @return bool
     */
    public function getIsInStock(): bool;

    /**
     * @param bool $isInStock
     * @return $this
     */
    public function setIsInStock(bool $isInStock): self;

    /**
     * @return bool
     */
    public function getManageStock(): bool;

    /**
     * @param bool $manageStock
     * @return $this
     */
    public function setManageStock(bool $manageStock): self;

    /**
     * @return bool
     */
    public function getUseConfigManageStock(): bool;

    /**
     * @param bool $useConfigManageStock
     * @return $this
     */
    public function setUseConfigManageStock(bool $useConfigManageStock): self;

    /**
     * @return int
     */
    public function getBackorders(): int;

    /**
     * @param int $backorders
     * @return $this
     */
    public function setBackorders(int $backorders): self;

    /**
     * @return float|null
     */
    public function getMinQty(): ?float;

    /**
     * @param float|null $minQty
     * @return $this
     */
    public function setMinQty(?float $minQty): self;

    /**
     * @return float|null
     */
    public function getMinSaleQty(): ?float;

    /**
     * @param float|null $minSaleQty
     * @return $this
     */
    public function setMinSaleQty(?float $minSaleQty): self;

    /**
     * @return float|null
     */
    public function getMaxSaleQty(): ?float;

    /**
     * @param float|null $maxSaleQty
     * @return $this
     */
    public function setMaxSaleQty(?float $maxSaleQty): self;

    /**
     * @return float|null
     */
    public function getNotifyStockQty(): ?float;

    /**
     * @param float|null $notifyStockQty
     * @return $this
     */
    public function setNotifyStockQty(?float $notifyStockQty): self;

    /**
     * @return bool
     */
    public function getEnableQtyIncrements(): bool;

    /**
     * @param bool $enableQtyIncrements
     * @return $this
     */
    public function setEnableQtyIncrements(bool $enableQtyIncrements): self;

    /**
     * @return float|null
     */
    public function getQtyIncrements(): ?float;

    /**
     * @param float|null $qtyIncrements
     * @return $this
     */
    public function setQtyIncrements(?float $qtyIncrements): self;
}
