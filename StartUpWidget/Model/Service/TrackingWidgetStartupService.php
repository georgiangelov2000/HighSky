<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\Model\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingWidgetStartupResponseInterface;
use HighSky\StartUpWidget\Api\Response\TrackingWidgetStartupResponseBuilderInterface;
use HighSky\StartUpWidget\Api\Service\TrackingWidgetStartupServiceInterface;
use HighSky\Shared\Model\Config\Tracking\TrackingConfig;

class TrackingWidgetStartupService implements TrackingWidgetStartupServiceInterface
{
    public function __construct(
        private readonly TrackingConfig $trackingConfig,
        private readonly TrackingWidgetStartupResponseBuilderInterface $trackingWidgetStartupResponseBuilder
    ) {}

    public function execute(): TrackingWidgetStartupResponseInterface
    {
        return $this->trackingWidgetStartupResponseBuilder->build(
            $this->trackingConfig->isWidgetEnabled()
        );
    }
}
