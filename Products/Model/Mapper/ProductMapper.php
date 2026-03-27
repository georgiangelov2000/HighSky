<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Mapper;

use HighSky\Products\Api\Data\ProductSyncItemInterface;
use HighSky\Products\Api\Mapper\ProductMapperInterface;
use HighSky\Products\Model\Data\ProductSyncItemFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\Product\Media\Config as MediaConfig;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProductType;

class ProductMapper implements ProductMapperInterface
{
    /**
     * @var array<int, string>
     */
    private array $categoryNameCache = [];

    public function __construct(
        private readonly ProductSyncItemFactory $productSyncItemFactory,
        private readonly ProductStatus $productStatus,
        private readonly MediaConfig $mediaConfig,
        private readonly StockRegistryInterface $stockRegistry,
        private readonly CategoryCollectionFactory $categoryCollectionFactory,
        private readonly ConfigurableProductType $configurableProductType
    ) {}

    public function map(Product $product): ProductSyncItemInterface
    {
        return $this->mapProduct($product, true);
    }

    private function mapProduct(Product $product, bool $includeVariants): ProductSyncItemInterface
    {
        $stockItem = $this->stockRegistry->getStockItem((int) $product->getId());
        $categoryIds = array_map('intval', $product->getCategoryIds() ?: []);
        $item = $this->productSyncItemFactory->create();
        $item->setId((int) $product->getId());
        $item->setSku((string) $product->getSku());
        $item->setName((string) $product->getName());
        $item->setPrice($this->formatDecimal($product->getPrice()));
        $item->setSpecialPrice($this->formatDecimal($product->getData('special_price')));
        $item->setSpecialFromDate($product->getData('special_from_date') ?: null);
        $item->setSpecialToDate($product->getData('special_to_date') ?: null);
        $item->setCost($this->formatDecimal($product->getData('cost')));
        $item->setTaxClassId($product->getData('tax_class_id') !== null ? (int) $product->getData('tax_class_id') : null);
        $item->setCategoryNames($this->getCategoryNames($categoryIds));
        $item->setCreatedAt((string) $product->getData('created_at'));
        $item->setUpdatedAt((string) $product->getData('updated_at'));
        $item->setStatus($this->getStatusLabel((int) $product->getStatus()));
        $item->setVisibility($this->getVisibilityLabel((int) $product->getVisibility()));
        $item->setImageUrl($this->getImageUrl($product));
        $item->setQty($stockItem && $stockItem->getQty() !== null ? (float) $stockItem->getQty() : null);
        $item->setIsInStock($stockItem ? (bool) $stockItem->getIsInStock() : false);
        $item->setManageStock($stockItem ? (bool) $stockItem->getManageStock() : false);
        $item->setUseConfigManageStock($stockItem ? (bool) $stockItem->getUseConfigManageStock() : false);
        $item->setBackorders($stockItem ? (int) $stockItem->getBackorders() : 0);
        $item->setMinQty($stockItem && $stockItem->getMinQty() !== null ? (float) $stockItem->getMinQty() : null);
        $item->setMinSaleQty($stockItem && $stockItem->getMinSaleQty() !== null ? (float) $stockItem->getMinSaleQty() : null);
        $item->setMaxSaleQty($stockItem && $stockItem->getMaxSaleQty() !== null ? (float) $stockItem->getMaxSaleQty() : null);
        $item->setNotifyStockQty($stockItem && $stockItem->getNotifyStockQty() !== null ? (float) $stockItem->getNotifyStockQty() : null);
        $item->setEnableQtyIncrements($stockItem ? (bool) $stockItem->getEnableQtyIncrements() : false);
        $item->setQtyIncrements($stockItem && $stockItem->getQtyIncrements() !== null ? (float) $stockItem->getQtyIncrements() : null);
        $item->setVariants($includeVariants ? $this->getVariants($product) : []);

        return $item;
    }

    /**
     * @return ProductSyncItemInterface[]
     */
    private function getVariants(Product $product): array
    {
        if ($product->getTypeId() !== ConfigurableProductType::TYPE_CODE) {
            return [];
        }

        $variants = [];
        foreach ($this->configurableProductType->getUsedProducts($product) as $variantProduct) {
            $variants[] = $this->mapProduct($variantProduct, false);
        }

        return $variants;
    }

    private function getStatusLabel(int $status): string
    {
        return (string) ($this->productStatus->getOptionText((string) $status) ?: $status);
    }

    private function getVisibilityLabel(int $visibility): string
    {
        return (string) (ProductVisibility::getOptionText($visibility) ?: $visibility);
    }

    private function getImageUrl(Product $product): ?string
    {
        $image = (string) $product->getData('image');
        if ($image === '' || $image === 'no_selection') {
            return null;
        }

        return $this->mediaConfig->getMediaUrl($image);
    }

    /**
     * @param int[] $categoryIds
     * @return string[]
     */
    private function getCategoryNames(array $categoryIds): array
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

    private function formatDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 2, '.', '');
    }
}
