<?php
declare(strict_types=1);

namespace HighSky\Products\Model;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;
use HighSky\Products\Api\ProductSyncInterface;
use HighSky\Products\Api\Service\ProductSyncServiceInterface;

class ProductSync implements ProductSyncInterface
{
    public function __construct(
        private readonly ProductSyncServiceInterface $productSyncService
    ) {}

    public function execute(
        $perPage = null,
        ?string $updateAfter = null
    ): ProductSyncResponseInterface {
        return $this->productSyncService->execute($perPage, $updateAfter);
    }
}
