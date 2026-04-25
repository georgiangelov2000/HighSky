<?php
declare(strict_types=1);

namespace HighSky\Products\Model\ProductSync\Repository;

use HighSky\Products\Api\ProductSync\Service\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory
    ) {}

    public function getList(?string $updateAfter, int $perPage, int $currentPage, array $enabledColumns): array
    {
        $collection = $this->buildCollection($updateAfter, $enabledColumns);
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

    private function buildCollection(?string $updateAfter, array $enabledColumns): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $attributeCodes = [];
        foreach ($enabledColumns as $enabledColumn) {
            foreach ($this->getAttributeCodesForColumn($enabledColumn) as $attributeCode) {
                $attributeCodes[$attributeCode] = $attributeCode;
            }
        }

        if ($attributeCodes !== []) {
            $collection->addAttributeToSelect(array_values($attributeCodes));
        }

        // Exclude simple products that are children of a configurable — they appear
        // as variants on their parent and should not be top-level results.
        $collection->getSelect()->joinLeft(
            ['super_link' => $collection->getTable('catalog_product_super_link')],
            'e.entity_id = super_link.product_id',
            []
        )->where('super_link.product_id IS NULL');

        if ($updateAfter !== null) {
            $connection = $collection->getConnection();
            $createdAtCondition = $connection->quoteInto('e.created_at > ?', $updateAfter);
            $updatedAtCondition = $connection->quoteInto('e.updated_at > ?', $updateAfter);
            $collection->getSelect()->where(sprintf('(%s OR %s)', $createdAtCondition, $updatedAtCondition));
        }

        return $collection;
    }

    /**
     * @return string[]
     */
    private function getAttributeCodesForColumn(string $column): array
    {
        return match ($column) {
            'name' => ['name'],
            'price' => ['price'],
            'special_price' => ['special_price'],
            'special_from_date' => ['special_from_date'],
            'special_to_date' => ['special_to_date'],
            'cost' => ['cost'],
            'tax_class_id' => ['tax_class_id'],
            'status' => ['status'],
            'visibility' => ['visibility'],
            'image_url' => ['image'],
            'variants' => ['name', 'price', 'special_price', 'special_from_date', 'special_to_date', 'cost', 'tax_class_id', 'status', 'visibility', 'image'],
            default => [],
        };
    }
}
