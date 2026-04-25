<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\Api;

use HighSky\Shared\Api\Tracking\Data\TrackingWidgetStartupResponseInterface;

interface TrackingWidgetStartupInterface
{
    /**
     * REST entry point for widget startup configuration.
     *
     * @return TrackingWidgetStartupResponseInterface
     */
    public function execute(): TrackingWidgetStartupResponseInterface;
}
