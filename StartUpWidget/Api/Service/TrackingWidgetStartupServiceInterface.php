<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\Api\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingWidgetStartupResponseInterface;

interface TrackingWidgetStartupServiceInterface
{
    /**
     * Return widget startup configuration for the client.
     *
     * @return TrackingWidgetStartupResponseInterface
     */
    public function execute(): TrackingWidgetStartupResponseInterface;
}
