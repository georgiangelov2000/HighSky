<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Service;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;
use HighSky\Products\Api\Mapper\ProductMapperInterface;
use HighSky\Products\Api\Response\ResponseBuilderInterface;
use HighSky\Products\Api\Service\ProductRepositoryInterface;
use HighSky\Products\Api\Service\ProductSyncServiceInterface;
use HighSky\Products\Api\Validator\RequestValidatorInterface;

class ProductSyncService implements ProductSyncServiceInterface
{
    public function __construct(
        private readonly RequestValidatorInterface $requestValidator,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductMapperInterface $productMapper,
        private readonly ResponseBuilderInterface $responseBuilder
    ) {}

    public function execute(
        $perPage = null,
        ?string $updateAfter = null
    ): ProductSyncResponseInterface {
        $validated = $this->requestValidator->validate($perPage, $updateAfter);

        $result = $this->productRepository->getList(
            $validated['update_after'],
            $validated['per_page'],
            $validated['current_page']
        );

        $products = [];
        foreach ($result['items'] as $product) {
            $products[] = $this->productMapper->map($product);
        }

        return $this->responseBuilder->build(
            $validated['update_after'],
            $products,
            $validated['per_page'],
            $result['total_count']
        );
    }
}
