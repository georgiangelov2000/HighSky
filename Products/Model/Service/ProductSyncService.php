<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Service;

use HighSky\Products\Api\Data\ProductSyncResponseInterface;
use HighSky\Products\Api\Mapper\ProductMapperInterface;
use HighSky\Products\Api\Response\ResponseBuilderInterface;
use HighSky\Products\Api\Service\ProductRepositoryInterface;
use HighSky\Products\Api\Service\ProductSyncServiceInterface;
use HighSky\Products\Api\Validator\RequestValidatorInterface;
use HighSky\Products\Model\Config\SyncConfig;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class ProductSyncService implements ProductSyncServiceInterface
{
    public function __construct(
        private readonly RequestValidatorInterface $requestValidator,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductMapperInterface $productMapper,
        private readonly ResponseBuilderInterface $responseBuilder,
        private readonly TimezoneInterface $timezone
    ) {}

    public function execute(
        ?string $status = null,
        ?string $from = null,
        ?string $to = null,
        $limit = null,
        $offset = null
    ): ProductSyncResponseInterface {
        $validated = $this->requestValidator->validate($status, $from, $to, $limit, $offset);
        [$windowFrom, $windowTo] = $this->resolveWindow($validated['from'], $validated['to']);

        $result = $this->productRepository->getList(
            $validated['status'],
            $windowFrom,
            $windowTo,
            $validated['limit'],
            $validated['offset']
        );

        $products = [];
        foreach ($result['items'] as $product) {
            $products[] = $this->productMapper->map($product);
        }

        $updatedAfter = $validated['status'] === SyncConfig::STATUS_OLD ? $windowFrom : null;

        return $this->responseBuilder->build(
            $validated['status'],
            $products,
            $validated['limit'],
            $validated['offset'],
            $result['has_more'],
            $updatedAfter
        );
    }

    /**
     * @return array{0:string,1:string}
     */
    private function resolveWindow(?string $from, ?string $to): array
    {
        if ($from !== null && $to !== null) {
            return [$from, $to];
        }

        $now = $this->timezone->date();
        $windowTo = $now->setTime(23, 59, 59)->format(SyncConfig::DATE_FORMAT);
        $windowFrom = $now->modify(sprintf('-%d days', SyncConfig::FALLBACK_DAYS))
            ->setTime(0, 0, 0)
            ->format(SyncConfig::DATE_FORMAT);

        return [$windowFrom, $windowTo];
    }
}
