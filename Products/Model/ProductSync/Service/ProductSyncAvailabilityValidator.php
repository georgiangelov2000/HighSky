<?php
declare(strict_types=1);

namespace HighSky\Products\Model\ProductSync\Service;

use HighSky\Shared\Model\Config\GeneralConfig;
use HighSky\Products\Model\Config\ProductSync\ProductSyncConfig;
use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception as WebapiException;

class ProductSyncAvailabilityValidator
{
    public function __construct(
        private readonly GeneralConfig $generalConfig,
        private readonly ProductSyncConfig $productSyncConfig
    ) {}

    public function validate(): void
    {
        if (!$this->generalConfig->isModuleEnabled()) {
            throw new WebapiException(
                new Phrase('The HighSky module is disabled.'),
                0,
                WebapiException::HTTP_FORBIDDEN
            );
        }

        if (!$this->productSyncConfig->isEnabled()) {
            throw new WebapiException(
                new Phrase('The HighSky product sync API is disabled.'),
                0,
                WebapiException::HTTP_FORBIDDEN
            );
        }
    }
}
