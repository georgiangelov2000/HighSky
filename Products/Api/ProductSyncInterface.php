<?php
declare(strict_types=1);

namespace HighSky\Products\Api;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;

interface ProductSyncInterface
{
    /**
     * REST entry point for product synchronization.
     *
     * @param int|string|null $perPage Number of records to return per page
     * @param string|null $updateAfter Datetime in Y-m-d H:i:s format
     * @return ProductSyncResponseInterface
     */
    public function execute(
        $perPage = null,
        ?string $updateAfter = null
    ): ProductSyncResponseInterface;
}
