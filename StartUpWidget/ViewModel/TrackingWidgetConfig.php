<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\ViewModel;

use HighSky\Shared\Model\Config\GeneralConfig;
use HighSky\Shared\Model\Config\Tracking\TrackingConfig;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class TrackingWidgetConfig implements ArgumentInterface
{
    public function __construct(
        private readonly GeneralConfig $generalConfig,
        private readonly TrackingConfig $trackingConfig,
        private readonly CustomerSession $customerSession,
        private readonly CheckoutSession $checkoutSession,
        private readonly Json $json
    ) {}

    public function isEnabled(): bool
    {
        return $this->generalConfig->isModuleEnabled()
            && $this->trackingConfig->isTrackingEnabled()
            && $this->trackingConfig->isWidgetEnabled();
    }

    public function getTenantId(): string
    {
        return $this->generalConfig->getTenantId();
    }

    public function getScriptUrl(): string
    {
        return $this->trackingConfig->getWidgetScriptUrl() . '?v=' . date('Y-m-d\TH');
    }

    public function getApiBaseUrl(): string
    {
        $scriptUrl = $this->trackingConfig->getWidgetScriptUrl();
        $parts = parse_url($scriptUrl);

        return ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '');
    }

    public function getCartSessionId(): ?int
    {
        $id = $this->checkoutSession->getQuoteId();

        return $id ? (int) $id : null;
    }

    public function getUserIdJson(): string
    {
        $id = $this->customerSession->getCustomerId();

        return $id ? (string) (int) $id : 'null';
    }
}
