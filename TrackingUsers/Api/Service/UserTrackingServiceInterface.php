<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Api\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingUserResponseInterface;

interface UserTrackingServiceInterface
{
    /**
     * Build the user tracking payload for a customer identifier.
     *
     * @param int $userId
     * @return TrackingUserResponseInterface
     */
    public function execute(int $userId): TrackingUserResponseInterface;
}
