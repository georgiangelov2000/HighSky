<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class GeneralConfig
{
    public const XML_PATH_MODULE_ENABLED = 'highsky_products/general/module_enabled';
    public const XML_PATH_TENANT_ID = 'highsky_products/general/tenant_id';
    public const XML_PATH_LEGACY_TENANT_ID = 'skycommerce/general/tenant_id';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    public function isModuleEnabled(?string $scopeCode = null): bool
    {
        $websiteValue = $this->scopeConfig->getValue(
            self::XML_PATH_MODULE_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($websiteValue !== null && $websiteValue !== '') {
            return $this->scopeConfig->isSetFlag(
                self::XML_PATH_MODULE_ENABLED,
                ScopeInterface::SCOPE_WEBSITE,
                $scopeCode
            );
        }

        return $this->scopeConfig->isSetFlag(self::XML_PATH_MODULE_ENABLED);
    }

    public function getTenantId(?string $scopeCode = null): string
    {
        $value = $this->getScopedValue(self::XML_PATH_TENANT_ID, $scopeCode);
        if ($value === '') {
            $value = $this->getScopedValue(self::XML_PATH_LEGACY_TENANT_ID, $scopeCode);
        }

        return $value;
    }

    private function getScopedValue(string $path, ?string $scopeCode = null): string
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
}
