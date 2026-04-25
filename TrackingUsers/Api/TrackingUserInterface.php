<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Api;

use HighSky\Shared\Api\Tracking\Data\TrackingUserResponseInterface;

interface TrackingUserInterface
{
    /**
     * REST entry point for user tracking payloads.
     *
     * @param string $userId
     * @return TrackingUserResponseInterface
     */
    public function execute(string $userId): TrackingUserResponseInterface;
}
