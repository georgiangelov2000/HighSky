<?php
declare(strict_types=1);

namespace HighSky\Products\Model\ProductSync\Mapper;

use HighSky\Products\Api\ProductSync\Data\ProductSyncItemInterface;
use HighSky\Products\Api\ProductSync\Mapper\ProductMapperInterface;
use HighSky\Products\Model\ProductSync\Data\ProductSyncItemFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Catalog\Model\Product\Media\Config as MediaConfig;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProductType;

class ProductMapper implements ProductMapperInterface
{
    public function __construct(
        private readonly ProductSyncItemFactory $productSyncItemFactory,
        private readonly ProductStatus $productStatus,
        private readonly MediaConfig $mediaConfig,
        private readonly StockRegistryInterface $stockRegistry,
        private readonly ConfigurableProductType $configurableProductType,
        private readonly CategoryNameResolver $categoryNameResolver,
        private readonly StockDataMapper $stockDataMapper
    ) {}

    public function map(Product $product, array $enabledColumns): ProductSyncItemInterface
    {
        return $this->mapProduct($product, $enabledColumns, true);
    }

    private function mapProduct(Product $product, array $enabledColumns, bool $includeVariants): ProductSyncItemInterface
    {
        $item = $this->productSyncItemFactory->create();
        $enabledColumns = array_fill_keys($enabledColumns, true);
        $stockItem = $this->stockDataMapper->requiresStockData($enabledColumns)
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
            $item->setCategoryNames($this->categoryNameResolver->resolve($categoryIds));
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

        $this->stockDataMapper->apply($item, $stockItem, $enabledColumns);

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

    private function formatDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 2, '.', '');
    }
}
