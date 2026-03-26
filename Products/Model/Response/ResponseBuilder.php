<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Response;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;
use HighSky\Products\Api\Response\ResponseBuilderInterface;
use HighSky\Products\Model\Config\SyncConfig;
use HighSky\Products\Model\Data\ProductSyncResponseFactory;

class ResponseBuilder implements ResponseBuilderInterface
{
    public function __construct(
        private readonly ProductSyncResponseFactory $productSyncResponseFactory
    ) {}

    public function build(
        string $status,
        array $products,
        int $limit,
        int $offset,
        bool $hasMore,
        ?string $updatedAfter = null
    ): ProductSyncResponseInterface {
        $response = $this->productSyncResponseFactory->create();
        $response->setStatus($status);
        $response->setCount(count($products));
        $response->setLimit($limit);
        $response->setOffset($offset);
        $response->setHasMore($hasMore);
        $response->setNextOffset($hasMore ? $offset + $limit : null);
        $response->setProducts($products);

        if ($status === SyncConfig::STATUS_OLD) {
            $response->setUpdatedAfter($updatedAfter);
        }

        return $response;
    }
}
