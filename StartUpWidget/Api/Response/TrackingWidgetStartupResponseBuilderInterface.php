<?php
declare(strict_types=1);

namespace HighSky\StartUpWidget\Api\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingWidgetStartupResponseInterface;

interface TrackingWidgetStartupResponseBuilderInterface
{
    /**
     * Build the widget startup response payload.
     *
     * @param bool $enabled
     * @return TrackingWidgetStartupResponseInterface
     */
    public function build(bool $enabled): TrackingWidgetStartupResponseInterface;
}
