<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Data;

interface TrackingWidgetStartupResponseInterface
{
    public const ENABLED = 'enabled';

    /**
     * @return bool
     */
    public function getEnabled(): bool;

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled): self;
}
