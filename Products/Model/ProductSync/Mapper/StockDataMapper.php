<?php
declare(strict_types=1);

namespace HighSky\Products\Model\ProductSync\Mapper;

use HighSky\Products\Api\ProductSync\Data\ProductSyncItemInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;

class StockDataMapper
{
    /**
     * @param array<string, bool> $enabledColumns
     */
    public function requiresStockData(array $enabledColumns): bool
    {
        foreach ($this->getStockColumns() as $stockColumn) {
            if (isset($enabledColumns[$stockColumn])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, bool> $enabledColumns
     */
    public function apply(ProductSyncItemInterface $item, ?StockItemInterface $stockItem, array $enabledColumns): void
    {
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
    }

    /**
     * @return string[]
     */
    private function getStockColumns(): array
    {
        return [
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
        ];
    }
}
