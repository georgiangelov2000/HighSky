<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

abstract class AbstractScopedConfig
{
    public function __construct(
        protected readonly ScopeConfigInterface $scopeConfig
    ) {}

    protected function isWebsiteFlagSet(string $path, ?string $scopeCode = null): bool
    {
        $websiteValue = $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($websiteValue !== null && $websiteValue !== '') {
            return $this->scopeConfig->isSetFlag(
                $path,
                ScopeInterface::SCOPE_WEBSITE,
                $scopeCode
            );
        }

        return $this->scopeConfig->isSetFlag($path);
    }

    protected function getScopedValue(string $path, ?string $scopeCode = null): string
    {
        $value = (string) $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($value === '') {
            $value = (string) $this->scopeConfig->getValue($path);
        }

        return trim($value);
    }

    protected function getPositiveIntegerValue(string $path, int $fallback, ?string $scopeCode = null): int
    {
        $value = (int) $this->getScopedValue($path, $scopeCode);

        return $value > 0 ? $value : $fallback;
    }
}
