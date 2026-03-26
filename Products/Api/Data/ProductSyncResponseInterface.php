<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Data;

interface ProductSyncResponseInterface
{
    public const STATUS = 'status';
    public const UPDATED_AFTER = 'updated_after';
    public const COUNT = 'count';
    public const LIMIT = 'limit';
    public const OFFSET = 'offset';
    public const HAS_MORE = 'has_more';
    public const NEXT_OFFSET = 'next_offset';
    public const PRODUCTS = 'products';

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
     * @return string|null
     */
    public function getUpdatedAfter(): ?string;

    /**
     * @param string|null $updatedAfter
     * @return $this
     */
    public function setUpdatedAfter(?string $updatedAfter): self;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count): self;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit): self;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset(int $offset): self;

    /**
     * @return bool
     */
    public function getHasMore(): bool;

    /**
     * @param bool $hasMore
     * @return $this
     */
    public function setHasMore(bool $hasMore): self;

    /**
     * @return int|null
     */
    public function getNextOffset(): ?int;

    /**
     * @param int|null $nextOffset
     * @return $this
     */
    public function setNextOffset(?int $nextOffset): self;

    /**
     * @return \HighSky\Products\Api\Data\ProductSyncItemInterface[]
     */
    public function getProducts(): array;

    /**
     * @param \HighSky\Products\Api\Data\ProductSyncItemInterface[] $products
     * @return $this
     */
    public function setProducts(array $products): self;
}
