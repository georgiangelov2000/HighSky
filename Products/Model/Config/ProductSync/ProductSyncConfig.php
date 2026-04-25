<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Config\ProductSync;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ProductSyncConfig
{
    public const XML_PATH_ENABLED = 'highsky_products/product_sync/enabled';
    public const XML_PATH_DEFAULT_PER_PAGE = 'highsky_products/product_sync/default_per_page';
    public const XML_PATH_MAX_PER_PAGE = 'highsky_products/product_sync/max_per_page';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    public function isEnabled(?string $scopeCode = null): bool
    {
        $websiteValue = $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($websiteValue !== null && $websiteValue !== '') {
            return $this->scopeConfig->isSetFlag(
                self::XML_PATH_ENABLED,
                ScopeInterface::SCOPE_WEBSITE,
                $scopeCode
            );
        }

        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED);
    }

    public function getDefaultPerPage(?string $scopeCode = null): int
    {
        return $this->normalizePositiveInteger(
            $this->getScopedValue(self::XML_PATH_DEFAULT_PER_PAGE, $scopeCode),
            SyncConfig::DEFAULT_PER_PAGE
        );
    }

    public function getMaxPerPage(?string $scopeCode = null): int
    {
        return max(
            SyncConfig::MIN_PER_PAGE,
            $this->normalizePositiveInteger(
                $this->getScopedValue(self::XML_PATH_MAX_PER_PAGE, $scopeCode),
                SyncConfig::MAX_PER_PAGE
            )
        );
    }

    private function getScopedValue(string $path, ?string $scopeCode = null): ?string
    {
        $value = $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($value === null || $value === '') {
            $value = $this->scopeConfig->getValue($path);
        }

        return $value !== null ? (string) $value : null;
    }

    private function normalizePositiveInteger(?string $value, int $fallback): int
    {
        if ($value === null || trim($value) === '') {
            return $fallback;
        }

        $normalizedValue = (int) $value;
        if ($normalizedValue <= 0) {
            return $fallback;
        }

        return $normalizedValue;
    }
}
