<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class SecurityConfig
{
    public const XML_PATH_RATE_LIMIT_ENABLED = 'highsky_products/security/rate_limit_enabled';
    public const XML_PATH_REQUESTS_PER_MINUTE = 'highsky_products/security/requests_per_minute';
    public const DEFAULT_REQUESTS_PER_MINUTE = 60;

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    public function isRateLimitEnabled(?string $scopeCode = null): bool
    {
        $websiteValue = $this->scopeConfig->getValue(
            self::XML_PATH_RATE_LIMIT_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($websiteValue !== null && $websiteValue !== '') {
            return $this->scopeConfig->isSetFlag(
                self::XML_PATH_RATE_LIMIT_ENABLED,
                ScopeInterface::SCOPE_WEBSITE,
                $scopeCode
            );
        }

        return $this->scopeConfig->isSetFlag(self::XML_PATH_RATE_LIMIT_ENABLED);
    }

    public function getRequestsPerMinute(?string $scopeCode = null): int
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_REQUESTS_PER_MINUTE,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($value === null || $value === '') {
            $value = $this->scopeConfig->getValue(self::XML_PATH_REQUESTS_PER_MINUTE);
        }

        $normalizedValue = (int) $value;
        if ($normalizedValue <= 0) {
            return self::DEFAULT_REQUESTS_PER_MINUTE;
        }

        return $normalizedValue;
    }
}
