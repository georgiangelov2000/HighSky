<?php
declare(strict_types=1);

namespace HighSky\Products\Model\ProductSync\Mapper;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class CategoryNameResolver
{
    /**
     * @var array<int, string>
     */
    private array $categoryNameCache = [];

    public function __construct(
        private readonly CategoryCollectionFactory $categoryCollectionFactory
    ) {}

    /**
     * @param int[] $categoryIds
     * @return string[]
     */
    public function resolve(array $categoryIds): array
    {
        $categoryIds = array_values(array_unique(array_filter($categoryIds)));
        $missingIds = array_diff($categoryIds, array_keys($this->categoryNameCache));

        if (!empty($missingIds)) {
            $collection = $this->categoryCollectionFactory->create();
            $collection->addAttributeToSelect('name');
            $collection->addFieldToFilter('entity_id', ['in' => $missingIds]);

            foreach ($collection as $category) {
                $this->categoryNameCache[(int) $category->getId()] = (string) $category->getName();
            }
        }

        $names = [];
        foreach ($categoryIds as $categoryId) {
            if (isset($this->categoryNameCache[$categoryId]) && $this->categoryNameCache[$categoryId] !== '') {
                $names[] = $this->categoryNameCache[$categoryId];
            }
        }

        return array_values($names);
    }
}
