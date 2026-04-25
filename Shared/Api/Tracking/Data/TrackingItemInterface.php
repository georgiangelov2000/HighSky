<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Data;

interface TrackingItemInterface
{
    public const PRODUCT_ID = 'product_id';
    public const SKU = 'sku';
    public const NAME = 'name';
    public const QTY = 'qty';

    /**
     * @return string|null
     */
    public function getProductId(): ?string;

    /**
     * @param string|null $productId
     * @return $this
     */
    public function setProductId(?string $productId): self;

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
     * @return float
     */
    public function getQty(): float;

    /**
     * @param float $qty
     * @return $this
     */
    public function setQty(float $qty): self;
}
