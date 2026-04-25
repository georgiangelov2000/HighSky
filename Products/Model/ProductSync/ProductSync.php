<?php
declare(strict_types=1);

namespace HighSky\Products\Model\ProductSync;

use HighSky\Products\Api\ProductSync\Data\ProductSyncResponseInterface;
use HighSky\Products\Api\ProductSync\ProductSyncInterface;
use HighSky\Products\Api\ProductSync\Service\ProductSyncServiceInterface;
use HighSky\Products\Model\ProductSync\Service\ProductSyncAvailabilityValidator;
use HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface;
use HighSky\Shared\Model\Security\RequestRateLimiter;

class ProductSync implements ProductSyncInterface
{
    public function __construct(
        private readonly ProductSyncServiceInterface $productSyncService,
        private readonly ProductSyncAvailabilityValidator $productSyncAvailabilityValidator,
        private readonly AuthHeaderValidatorInterface $authHeaderValidator,
        private readonly RequestRateLimiter $requestRateLimiter
    ) {}

    public function execute(
        $perPage = null,
        ?string $updateAfter = null
    ): ProductSyncResponseInterface {
        $this->productSyncAvailabilityValidator->validate();
        $this->requestRateLimiter->validate('products_sync');
        $this->authHeaderValidator->validate();

        return $this->productSyncService->execute($perPage, $updateAfter);
    }
}
