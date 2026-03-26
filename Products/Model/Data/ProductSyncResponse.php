<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Data;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class ProductSyncResponse extends AbstractSimpleObject implements ProductSyncResponseInterface
{
    /**
     * @inheritdoc
     */
    public function getUpdateAfter(): ?string
    {
        $updateAfter = $this->_get(self::UPDATE_AFTER);

        return $updateAfter !== null ? (string) $updateAfter : null;
    }

    /**
     * @inheritdoc
     */
    public function setUpdateAfter(?string $updateAfter): ProductSyncResponseInterface
    {
        return $this->setData(self::UPDATE_AFTER, $updateAfter);
    }

    /**
     * @inheritdoc
     */
    public function getPerPage(): int
    {
        return (int) $this->_get(self::PER_PAGE);
    }

    /**
     * @inheritdoc
     */
    public function setPerPage(int $perPage): ProductSyncResponseInterface
    {
        return $this->setData(self::PER_PAGE, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function getCurrentPage(): int
    {
        return (int) $this->_get(self::CURRENT_PAGE);
    }

    /**
     * @inheritdoc
     */
    public function setCurrentPage(int $currentPage): ProductSyncResponseInterface
    {
        return $this->setData(self::CURRENT_PAGE, $currentPage);
    }

    /**
     * @inheritdoc
     */
    public function getTotalCount(): int
    {
        return (int) $this->_get(self::TOTAL_COUNT);
    }

    /**
     * @inheritdoc
     */
    public function setTotalCount(int $totalCount): ProductSyncResponseInterface
    {
        return $this->setData(self::TOTAL_COUNT, $totalCount);
    }

    /**
     * @inheritdoc
     */
    public function getTotalPages(): int
    {
        return (int) $this->_get(self::TOTAL_PAGES);
    }

    /**
     * @inheritdoc
     */
    public function setTotalPages(int $totalPages): ProductSyncResponseInterface
    {
        return $this->setData(self::TOTAL_PAGES, $totalPages);
    }

    /**
     * @return \HighSky\Products\Api\Data\ProductSyncItemInterface[]
     */
    public function getProducts()
    {
        return $this->_get(self::PRODUCTS) ?? [];
    }

    /**
     * @param \HighSky\Products\Api\Data\ProductSyncItemInterface[] $products
     * @return \HighSky\Products\Api\Data\ProductSyncResponseInterface
     */
    public function setProducts(array $products): ProductSyncResponseInterface
    {
        return $this->setData(self::PRODUCTS, $products);
    }
}
