<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\Model\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingWidgetStartupResponseInterface;
use HighSky\StartUpWidget\Api\Response\TrackingWidgetStartupResponseBuilderInterface;
use HighSky\Shared\Model\Tracking\Data\TrackingWidgetStartupResponseFactory;

class TrackingWidgetStartupResponseBuilder implements TrackingWidgetStartupResponseBuilderInterface
{
    public function __construct(
        private readonly TrackingWidgetStartupResponseFactory $trackingWidgetStartupResponseFactory
    ) {}

    public function build(bool $enabled): TrackingWidgetStartupResponseInterface
    {
        $response = $this->trackingWidgetStartupResponseFactory->create();
        $response->setEnabled($enabled);

        return $response;
    }
}
