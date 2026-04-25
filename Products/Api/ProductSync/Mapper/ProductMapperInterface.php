<?php
declare(strict_types=1);

namespace HighSky\Products\Api\ProductSync\Mapper;

use HighSky\Products\Api\ProductSync\Data\ProductSyncItemInterface;
use Magento\Catalog\Model\Product;

interface ProductMapperInterface
{
    /**
     * Convert a Magento product entity into the sync payload shape.
     *
     * @param Product $product
     * @return ProductSyncItemInterface
     */
    public function map(Product $product): ProductSyncItemInterface;
}
