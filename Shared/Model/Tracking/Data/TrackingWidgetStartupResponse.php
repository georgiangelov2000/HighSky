<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Data;

use HighSky\Shared\Api\Tracking\Data\TrackingWidgetStartupResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class TrackingWidgetStartupResponse extends AbstractSimpleObject implements TrackingWidgetStartupResponseInterface
{
    public function getEnabled(): bool
    {
        return (bool) $this->_get(self::ENABLED);
    }

    public function setEnabled(bool $enabled): TrackingWidgetStartupResponseInterface
    {
        return $this->setData(self::ENABLED, $enabled);
    }
}
