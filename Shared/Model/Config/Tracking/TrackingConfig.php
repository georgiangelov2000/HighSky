<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Config\Tracking;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

class TrackingConfig
{
    public const XML_PATH_ENABLED = 'highsky_products/tracking/enabled';
    public const XML_PATH_AUTH_REQUIRED = 'highsky_products/tracking/auth_required';
    public const XML_PATH_WIDGET_ENABLED = 'highsky_products/tracking/widget_enabled';
    public const XML_PATH_AUTH_TOKEN = 'highsky_products/tracking_authentication/auth_token';
    public const XML_PATH_USER_ORDER_HISTORY_LIMIT = 'highsky_products/tracking/user_order_history_limit';
    public const DEFAULT_USER_ORDER_HISTORY_LIMIT = 20;

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly EncryptorInterface $encryptor
    ) {}

    public function isTrackingEnabled(?string $scopeCode = null): bool
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

    public function isAuthRequired(?string $scopeCode = null): bool
    {
        $websiteValue = $this->scopeConfig->getValue(
            self::XML_PATH_AUTH_REQUIRED,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($websiteValue !== null && $websiteValue !== '') {
            return $this->scopeConfig->isSetFlag(
                self::XML_PATH_AUTH_REQUIRED,
                ScopeInterface::SCOPE_WEBSITE,
                $scopeCode
            );
        }

        return $this->scopeConfig->isSetFlag(self::XML_PATH_AUTH_REQUIRED);
    }

    public function isWidgetEnabled(?string $scopeCode = null): bool
    {
        $websiteValue = $this->scopeConfig->getValue(
            self::XML_PATH_WIDGET_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($websiteValue !== null && $websiteValue !== '') {
            return $this->scopeConfig->isSetFlag(
                self::XML_PATH_WIDGET_ENABLED,
                ScopeInterface::SCOPE_WEBSITE,
                $scopeCode
            );
        }

        return $this->scopeConfig->isSetFlag(self::XML_PATH_WIDGET_ENABLED);
    }

    public function getWidgetScriptUrl(?string $scopeCode = null): string
    {
        $value = (string) $this->scopeConfig->getValue(
            self::XML_PATH_WIDGET_SCRIPT_URL,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($value === '') {
            $value = (string) $this->scopeConfig->getValue(self::XML_PATH_WIDGET_SCRIPT_URL);
        }

        return trim($value);
    }

    public function getAuthToken(?string $scopeCode = null): string
    {
        $value = (string) $this->scopeConfig->getValue(
            self::XML_PATH_AUTH_TOKEN,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($value === '') {
            $value = (string) $this->scopeConfig->getValue(self::XML_PATH_AUTH_TOKEN);
        }

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
        $rawValue = $this->scopeConfig->getValue(
            self::XML_PATH_USER_ORDER_HISTORY_LIMIT,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        if ($rawValue === null || $rawValue === '') {
            $rawValue = $this->scopeConfig->getValue(self::XML_PATH_USER_ORDER_HISTORY_LIMIT);
        }

        $value = (int) $rawValue;

        if ($value <= 0) {
            return self::DEFAULT_USER_ORDER_HISTORY_LIMIT;
        }

        return $value;
    }
}
