<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Config\Tracking;

use HighSky\Shared\Model\Config\AbstractScopedConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class TrackingConfig extends AbstractScopedConfig
{
    public const XML_PATH_ENABLED = 'highsky_products/tracking/enabled';
    public const XML_PATH_AUTH_REQUIRED = 'highsky_products/tracking/auth_required';
    public const XML_PATH_WIDGET_ENABLED = 'highsky_products/tracking/widget_enabled';
    public const XML_PATH_WIDGET_SCRIPT_URL = 'highsky_products/tracking/widget_script_url';
    public const XML_PATH_AUTH_TOKEN = 'highsky_products/tracking_authentication/auth_token';
    public const XML_PATH_USER_ORDER_HISTORY_LIMIT = 'highsky_products/tracking/user_order_history_limit';
    public const DEFAULT_USER_ORDER_HISTORY_LIMIT = 20;
    public const DEFAULT_WIDGET_SCRIPT_URL = 'https://cfe.highsky.ai/highsky-chatwidget.js';

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        private readonly EncryptorInterface $encryptor
    ) {
        parent::__construct($scopeConfig);
    }

    public function isTrackingEnabled(?string $scopeCode = null): bool
    {
        return $this->isWebsiteFlagSet(self::XML_PATH_ENABLED, $scopeCode);
    }

    public function isAuthRequired(?string $scopeCode = null): bool
    {
        return $this->isWebsiteFlagSet(self::XML_PATH_AUTH_REQUIRED, $scopeCode);
    }

    public function isWidgetEnabled(?string $scopeCode = null): bool
    {
        return $this->isWebsiteFlagSet(self::XML_PATH_WIDGET_ENABLED, $scopeCode);
    }

    public function getWidgetScriptUrl(?string $scopeCode = null): string
    {
        $value = $this->getScopedValue(self::XML_PATH_WIDGET_SCRIPT_URL, $scopeCode);

        return $value !== '' ? $value : self::DEFAULT_WIDGET_SCRIPT_URL;
    }

    public function getAuthToken(?string $scopeCode = null): string
    {
        $value = $this->getScopedValue(self::XML_PATH_AUTH_TOKEN, $scopeCode);

        if ($value === '') {
            return '';
        }

        if (preg_match('/^\d+:\d+:/', $value) === 1) {
            return (string) $this->encryptor->decrypt($value);
        }

        return $value;
    }

    public function getUserOrderHistoryLimit(?string $scopeCode = null): int
    {
        return $this->getPositiveIntegerValue(
            self::XML_PATH_USER_ORDER_HISTORY_LIMIT,
            self::DEFAULT_USER_ORDER_HISTORY_LIMIT,
            $scopeCode
        );
    }
}
