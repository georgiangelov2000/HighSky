<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Response;

use HighSky\Products\Api\Data\ProductSyncItemInterface;
use HighSky\Products\Api\Data\ProductSyncResponseInterface;

interface ResponseBuilderInterface
{
    /**
     * Build the final REST response payload.
     *
     * @param string $status
     * @param ProductSyncItemInterface[] $products
     * @param int $limit
     * @param int $offset
     * @param bool $hasMore
     * @param string|null $updatedAfter
     * @return ProductSyncResponseInterface
     */
    public function build(
        string $status,
        array $products,
        int $limit,
        int $offset,
        bool $hasMore,
        ?string $updatedAfter = null
    ): ProductSyncResponseInterface;
}
