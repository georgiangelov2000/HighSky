<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Service;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;

interface ProductSyncServiceInterface
{
    /**
     * Orchestrate validation, filtering, mapping and response building.
     *
     * @param string|null $status
     * @param string|null $from
     * @param string|null $to
     * @param int|string|null $limit
     * @param int|string|null $offset
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
