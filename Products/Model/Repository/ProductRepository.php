<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Repository;

use HighSky\Products\Api\Service\ProductRepositoryInterface;
use HighSky\Products\Model\Config\ApiColumnsConfig;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory,
        private readonly ApiColumnsConfig $apiColumnsConfig
    ) {}

    public function getList(?string $updateAfter, int $perPage, int $currentPage): array
    {
        $collection = $this->buildCollection($updateAfter);
        $totalCount = (int) $collection->getSize();

        $collection->setPageSize($perPage);
        $collection->setCurPage($currentPage);
        $collection->addOrder('updated_at', Collection::SORT_ORDER_ASC);
        $collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);

        return [
            'items' => array_values($collection->getItems()),
            'total_count' => $totalCount,
        ];
    }

    private function buildCollection(?string $updateAfter): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect([
            'name',
            'price',
            'special_price',
            'special_from_date',
            'special_to_date',
            'cost',
            'status',
            'tax_class_id',
            'visibility',
            'image',
        ]);

        if ($updateAfter !== null) {
            $connection = $collection->getConnection();
            $createdAtCondition = $connection->quoteInto('e.created_at > ?', $updateAfter);
            $updatedAtCondition = $connection->quoteInto('e.updated_at > ?', $updateAfter);
            $collection->getSelect()->where(sprintf('(%s OR %s)', $createdAtCondition, $updatedAtCondition));
        }

        return $collection;
    }
}
