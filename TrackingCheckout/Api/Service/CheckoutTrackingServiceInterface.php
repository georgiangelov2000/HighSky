<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Api\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;

interface CheckoutTrackingServiceInterface
{
    /**
     * Build the checkout tracking payload for a session identifier.
     *
     * @param string $sessionId
     * @return TrackingCheckoutResponseInterface
     */
    public function execute(string $sessionId): TrackingCheckoutResponseInterface;
}
