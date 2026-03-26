<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Data;

interface ProductSyncResponseInterface
{
    public const UPDATE_AFTER = 'update_after';
    public const PER_PAGE = 'per_page';
    public const CURRENT_PAGE = 'current_page';
    public const TOTAL_COUNT = 'total_count';
    public const TOTAL_PAGES = 'total_pages';
    public const PRODUCTS = 'products';

    /**
     * @return string|null
     */
    public function getUpdateAfter(): ?string;

    /**
     * @param string|null $updateAfter
     * @return $this
     */
    public function setUpdateAfter(?string $updateAfter): self;

    /**
     * @return int
     */
    public function getPerPage(): int;

    /**
     * @param int $perPage
     * @return $this
     */
    public function setPerPage(int $perPage): self;

    /**
     * @return int
     */
    public function getCurrentPage(): int;

    /**
     * @param int $currentPage
     * @return $this
     */
    public function setCurrentPage(int $currentPage): self;

    /**
     * @return int
     */
    public function getTotalCount(): int;

    /**
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount(int $totalCount): self;

    /**
     * @return int
     */
    public function getTotalPages(): int;

    /**
     * @param int $totalPages
     * @return $this
     */
    public function setTotalPages(int $totalPages): self;

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
