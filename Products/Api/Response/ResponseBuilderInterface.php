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
     * @param string|null $updateAfter
     * @param ProductSyncItemInterface[] $products
     * @param int $perPage
     * @param int $currentPage
     * @param int $totalCount
     * @return ProductSyncResponseInterface
     */
    public function build(
        ?string $updateAfter,
        array $products,
        int $perPage,
        int $currentPage,
        int $totalCount
    ): ProductSyncResponseInterface;
}
