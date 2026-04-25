<?php
declare(strict_types=1);

namespace HighSky\Products\Api\ProductSync\Response;

use HighSky\Products\Api\ProductSync\Data\ProductSyncResponseInterface;

interface ResponseBuilderInterface
{
    /**
     * Build the final REST response payload.
     *
     * @param string|null $updateAfter
     * @param \HighSky\Products\Api\ProductSync\Data\ProductSyncItemInterface[] $products
     * @param int $perPage
     * @param int $totalCount
     * @return ProductSyncResponseInterface
     */
    public function build(
        ?string $updateAfter,
        array $products,
        int $perPage,
        int $totalCount
    ): ProductSyncResponseInterface;
}
