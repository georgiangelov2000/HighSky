<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\ViewModel;

use HighSky\Shared\Model\Config\GeneralConfig;
use HighSky\Shared\Model\Config\Tracking\TrackingConfig;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class TrackingWidgetConfig implements ArgumentInterface
{
    public function __construct(
        private readonly GeneralConfig $generalConfig,
        private readonly TrackingConfig $trackingConfig,
        private readonly CustomerSession $customerSession
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

    public function getUserIdJson(): string
    {
        $customerId = $this->customerSession->getCustomerId();
        return $customerId ? (string) (int) $customerId : 'null';
    }
}
