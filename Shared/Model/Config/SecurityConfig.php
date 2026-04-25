<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SecurityConfig extends AbstractScopedConfig
{
    public const XML_PATH_RATE_LIMIT_ENABLED = 'highsky_products/security/rate_limit_enabled';
    public const XML_PATH_REQUESTS_PER_MINUTE = 'highsky_products/security/requests_per_minute';
    public const DEFAULT_REQUESTS_PER_MINUTE = 60;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($scopeConfig);
    }

    public function isRateLimitEnabled(?string $scopeCode = null): bool
    {
        return $this->isWebsiteFlagSet(self::XML_PATH_RATE_LIMIT_ENABLED, $scopeCode);
    }

    public function getRequestsPerMinute(?string $scopeCode = null): int
    {
        return $this->getPositiveIntegerValue(
            self::XML_PATH_REQUESTS_PER_MINUTE,
            self::DEFAULT_REQUESTS_PER_MINUTE,
            $scopeCode
        );
    }
}
