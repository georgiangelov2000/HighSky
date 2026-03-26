<?php
declare(strict_types=1);

namespace HighSky\Products\Api;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;

interface ProductSyncInterface
{
    /**
     * REST entry point for product synchronization.
     *
     * @param string|null $status Allowed values: new, old
     * @param string|null $from Datetime in Y-m-d H:i:s format
     * @param string|null $to Datetime in Y-m-d H:i:s format
     * @param int|string|null $limit Number of records to return
     * @param int|string|null $offset Number of records to skip
     * @return ProductSyncResponseInterface
     */
    public function execute(
        ?string $status = null,
        ?string $from = null,
        ?string $to = null,
        $limit = null,
        $offset = null
    ): ProductSyncResponseInterface;
}
