<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Repository;

use HighSky\Products\Api\Service\ProductRepositoryInterface;
use HighSky\Products\Model\Config\SyncConfig;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory
    ) {}

    public function getList(string $status, string $from, string $to, int $limit, int $offset): array
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

        // Use a stable order across all requests to keep pagination deterministic.
        $collection->addOrder('entity_id', 'ASC');
        $collection->getSelect()->limit($limit + 1, $offset);

        $items = $collection->getItems();

        $hasMore = count($items) > $limit;
        if ($hasMore) {
            array_pop($items);
        }

        return [
            'items' => array_values($items),
            'has_more' => $hasMore,
        ];
    }
}
