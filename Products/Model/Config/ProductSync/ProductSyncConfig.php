<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Config\ProductSync;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ProductSyncConfig
{
    public const XML_PATH_ENABLED = 'highsky_products/product_sync/enabled';

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
}
