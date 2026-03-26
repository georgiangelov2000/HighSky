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
        ?string $status = null,
        ?string $from = null,
        ?string $to = null,
        $limit = null,
        $offset = null
    ): ProductSyncResponseInterface {
        return $this->productSyncService->execute($status, $from, $to, $limit, $offset);
    }
}
