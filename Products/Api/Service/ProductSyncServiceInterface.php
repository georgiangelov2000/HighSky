<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Service;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;

interface ProductSyncServiceInterface
{
    /**
     * Orchestrate validation, filtering, mapping and response building.
     *
     * @param int|string|null $perPage
     * @param string|null $updateAfter
     * @return ProductSyncResponseInterface
     */
    public function execute(
        $perPage = null,
        ?string $updateAfter = null
    ): ProductSyncResponseInterface;
}
