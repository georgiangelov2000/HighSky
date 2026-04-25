<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class GeneralConfig extends AbstractScopedConfig
{
    public const XML_PATH_MODULE_ENABLED = 'highsky_products/general/module_enabled';
    public const XML_PATH_TENANT_ID = 'highsky_products/general/tenant_id';
    public const XML_PATH_LEGACY_TENANT_ID = 'skycommerce/general/tenant_id';

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($scopeConfig);
    }

    public function isModuleEnabled(?string $scopeCode = null): bool
    {
        return $this->isWebsiteFlagSet(self::XML_PATH_MODULE_ENABLED, $scopeCode);
    }

    public function getTenantId(?string $scopeCode = null): string
    {
        $value = $this->getScopedValue(self::XML_PATH_TENANT_ID, $scopeCode);
        if ($value === '') {
            $value = $this->getScopedValue(self::XML_PATH_LEGACY_TENANT_ID, $scopeCode);
        }

        return $value;
    }
}
