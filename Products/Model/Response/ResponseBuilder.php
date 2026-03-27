<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Response;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;
use HighSky\Products\Api\Response\ResponseBuilderInterface;
use HighSky\Products\Model\Data\ProductSyncResponseFactory;

class ResponseBuilder implements ResponseBuilderInterface
{
    public function __construct(
        private readonly ProductSyncResponseFactory $productSyncResponseFactory
    ) {}

    public function build(
        ?string $updateAfter,
        array $products,
        int $perPage,
        int $totalCount
    ): ProductSyncResponseInterface {
        $response = $this->productSyncResponseFactory->create();
        $response->setUpdateAfter($updateAfter);
        $response->setPerPage($perPage);
        $response->setTotalCount($totalCount);
        $response->setTotalPages($perPage > 0 ? (int) ceil($totalCount / $perPage) : 0);
        $response->setProducts($products);

        return $response;
    }
}
