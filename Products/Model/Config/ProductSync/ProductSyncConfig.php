<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Config\ProductSync;

use HighSky\Shared\Model\Config\AbstractScopedConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ProductSyncConfig extends AbstractScopedConfig
{
    public const XML_PATH_ENABLED = 'highsky_products/product_sync/enabled';
    public const XML_PATH_DEFAULT_PER_PAGE = 'highsky_products/product_sync/default_per_page';
    public const XML_PATH_MAX_PER_PAGE = 'highsky_products/product_sync/max_per_page';

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($scopeConfig);
    }

    public function isEnabled(?string $scopeCode = null): bool
    {
        return $this->isWebsiteFlagSet(self::XML_PATH_ENABLED, $scopeCode);
    }

    public function getDefaultPerPage(?string $scopeCode = null): int
    {
        return $this->getPositiveIntegerValue(
            self::XML_PATH_DEFAULT_PER_PAGE,
            SyncConfig::DEFAULT_PER_PAGE,
            $scopeCode
        );
    }

    public function getMaxPerPage(?string $scopeCode = null): int
    {
        return max(
            SyncConfig::MIN_PER_PAGE,
            $this->getPositiveIntegerValue(
                self::XML_PATH_MAX_PER_PAGE,
                SyncConfig::MAX_PER_PAGE,
                $scopeCode
            )
        );
    }
}
