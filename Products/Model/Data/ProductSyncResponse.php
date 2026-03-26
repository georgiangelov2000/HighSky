<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Data;

use HighSky\Products\Api\Data\ProductSyncItemInterface;
use HighSky\Products\Api\Data\ProductSyncResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class ProductSyncResponse extends AbstractSimpleObject implements ProductSyncResponseInterface
{
    /**
     * @inheritdoc
     */
    public function getStatus(): string
    {
        return (string) $this->_get(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(string $status): ProductSyncResponseInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAfter(): ?string
    {
        $updatedAfter = $this->_get(self::UPDATED_AFTER);

        return $updatedAfter !== null ? (string) $updatedAfter : null;
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedAfter(?string $updatedAfter): ProductSyncResponseInterface
    {
        return $this->setData(self::UPDATED_AFTER, $updatedAfter);
    }

    /**
     * @inheritdoc
     */
    public function getCount(): int
    {
        return (int) $this->_get(self::COUNT);
    }

    /**
     * @inheritdoc
     */
    public function setCount(int $count): ProductSyncResponseInterface
    {
        return $this->setData(self::COUNT, $count);
    }

    /**
     * @inheritdoc
     */
    public function getLimit(): int
    {
        return (int) $this->_get(self::LIMIT);
    }

    /**
     * @inheritdoc
     */
    public function setLimit(int $limit): ProductSyncResponseInterface
    {
        return $this->setData(self::LIMIT, $limit);
    }

    /**
     * @inheritdoc
     */
    public function getOffset(): int
    {
        return (int) $this->_get(self::OFFSET);
    }

    /**
     * @inheritdoc
     */
    public function setOffset(int $offset): ProductSyncResponseInterface
    {
        return $this->setData(self::OFFSET, $offset);
    }

    /**
     * @inheritdoc
     */
    public function getHasMore(): bool
    {
        return (bool) $this->_get(self::HAS_MORE);
    }

    /**
     * @inheritdoc
     */
    public function setHasMore(bool $hasMore): ProductSyncResponseInterface
    {
        return $this->setData(self::HAS_MORE, $hasMore);
    }

    /**
     * @inheritdoc
     */
    public function getNextOffset(): ?int
    {
        $nextOffset = $this->_get(self::NEXT_OFFSET);

        return $nextOffset !== null ? (int) $nextOffset : null;
    }

    /**
     * @inheritdoc
     */
    public function setNextOffset(?int $nextOffset): ProductSyncResponseInterface
    {
        return $this->setData(self::NEXT_OFFSET, $nextOffset);
    }

    /**
     * @inheritdoc
     */
    public function getProducts(): array
    {
        return $this->_get(self::PRODUCTS) ?? [];
    }

    /**
     * @inheritdoc
     */
    public function setProducts(array $products): ProductSyncResponseInterface
    {
        return $this->setData(self::PRODUCTS, $products);
    }
}
