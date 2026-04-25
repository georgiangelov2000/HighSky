<?php
declare(strict_types=1);

namespace HighSky\Products\Model\ProductSync\Mapper;

use HighSky\Products\Api\ProductSync\Data\ProductSyncItemInterface;
use HighSky\Products\Api\ProductSync\Mapper\ProductMapperInterface;
use HighSky\Products\Model\ProductSync\Data\ProductSyncItemFactory;
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

    public function map(Product $product, array $enabledColumns): ProductSyncItemInterface
    {
        return $this->mapProduct($product, $enabledColumns, true);
    }

    private function mapProduct(Product $product, array $enabledColumns, bool $includeVariants): ProductSyncItemInterface
    {
        $item = $this->productSyncItemFactory->create();
        $enabledColumns = array_fill_keys($enabledColumns, true);
        $stockItem = $this->requiresStockData($enabledColumns)
            ? $this->stockRegistry->getStockItem((int) $product->getId())
            : null;

        if (isset($enabledColumns['id'])) {
            $item->setId((int) $product->getId());
        }

        if (isset($enabledColumns['sku'])) {
            $item->setSku((string) $product->getSku());
        }

        if (isset($enabledColumns['name'])) {
            $item->setName((string) $product->getName());
        }

        if (isset($enabledColumns['price'])) {
            $item->setPrice($this->formatDecimal($product->getPrice()));
        }

        if (isset($enabledColumns['special_price'])) {
            $item->setSpecialPrice($this->formatDecimal($product->getData('special_price')));
        }

        if (isset($enabledColumns['special_from_date'])) {
            $item->setSpecialFromDate($product->getData('special_from_date') ?: null);
        }

        if (isset($enabledColumns['special_to_date'])) {
            $item->setSpecialToDate($product->getData('special_to_date') ?: null);
        }

        if (isset($enabledColumns['cost'])) {
            $item->setCost($this->formatDecimal($product->getData('cost')));
        }

        if (isset($enabledColumns['tax_class_id'])) {
            $item->setTaxClassId($product->getData('tax_class_id') !== null ? (int) $product->getData('tax_class_id') : null);
        }

        if (isset($enabledColumns['category_names'])) {
            $categoryIds = array_map('intval', $product->getCategoryIds() ?: []);
            $item->setCategoryNames($this->getCategoryNames($categoryIds));
        }

        if (isset($enabledColumns['created_at'])) {
            $item->setCreatedAt((string) $product->getData('created_at'));
        }

        if (isset($enabledColumns['updated_at'])) {
            $item->setUpdatedAt((string) $product->getData('updated_at'));
        }

        if (isset($enabledColumns['status'])) {
            $item->setStatus($this->getStatusLabel((int) $product->getStatus()));
        }

        if (isset($enabledColumns['visibility'])) {
            $item->setVisibility($this->getVisibilityLabel((int) $product->getVisibility()));
        }

        if (isset($enabledColumns['image_url'])) {
            $item->setImageUrl($this->getImageUrl($product));
        }

        if (isset($enabledColumns['qty'])) {
            $item->setQty($stockItem && $stockItem->getQty() !== null ? (float) $stockItem->getQty() : null);
        }

        if (isset($enabledColumns['is_in_stock'])) {
            $item->setIsInStock($stockItem ? (bool) $stockItem->getIsInStock() : false);
        }

        if (isset($enabledColumns['manage_stock'])) {
            $item->setManageStock($stockItem ? (bool) $stockItem->getManageStock() : false);
        }

        if (isset($enabledColumns['use_config_manage_stock'])) {
            $item->setUseConfigManageStock($stockItem ? (bool) $stockItem->getUseConfigManageStock() : false);
        }

        if (isset($enabledColumns['backorders'])) {
            $item->setBackorders($stockItem ? (int) $stockItem->getBackorders() : 0);
        }

        if (isset($enabledColumns['min_qty'])) {
            $item->setMinQty($stockItem && $stockItem->getMinQty() !== null ? (float) $stockItem->getMinQty() : null);
        }

        if (isset($enabledColumns['min_sale_qty'])) {
            $item->setMinSaleQty($stockItem && $stockItem->getMinSaleQty() !== null ? (float) $stockItem->getMinSaleQty() : null);
        }

        if (isset($enabledColumns['max_sale_qty'])) {
            $item->setMaxSaleQty($stockItem && $stockItem->getMaxSaleQty() !== null ? (float) $stockItem->getMaxSaleQty() : null);
        }

        if (isset($enabledColumns['notify_stock_qty'])) {
            $item->setNotifyStockQty($stockItem && $stockItem->getNotifyStockQty() !== null ? (float) $stockItem->getNotifyStockQty() : null);
        }

        if (isset($enabledColumns['enable_qty_increments'])) {
            $item->setEnableQtyIncrements($stockItem ? (bool) $stockItem->getEnableQtyIncrements() : false);
        }

        if (isset($enabledColumns['qty_increments'])) {
            $item->setQtyIncrements($stockItem && $stockItem->getQtyIncrements() !== null ? (float) $stockItem->getQtyIncrements() : null);
        }

        if (isset($enabledColumns['variants'])) {
            $item->setVariants($includeVariants ? $this->getVariants($product, array_keys($enabledColumns)) : []);
        }

        return $item;
    }

    /**
     * @return ProductSyncItemInterface[]
     */
    private function getVariants(Product $product, array $enabledColumns): array
    {
        if ($product->getTypeId() !== ConfigurableProductType::TYPE_CODE) {
            return [];
        }

        $variants = [];
        foreach ($this->configurableProductType->getUsedProducts($product) as $variantProduct) {
            $variants[] = $this->mapProduct($variantProduct, $enabledColumns, false);
        }

        return $variants;
    }

    /**
     * @param array<string, bool> $enabledColumns
     */
    private function requiresStockData(array $enabledColumns): bool
    {
        foreach ([
            'qty',
            'is_in_stock',
            'manage_stock',
            'use_config_manage_stock',
            'backorders',
            'min_qty',
            'min_sale_qty',
            'max_sale_qty',
            'notify_stock_qty',
            'enable_qty_increments',
            'qty_increments',
        ] as $stockColumn) {
            if (isset($enabledColumns[$stockColumn])) {
                return true;
            }
        }

        return false;
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
