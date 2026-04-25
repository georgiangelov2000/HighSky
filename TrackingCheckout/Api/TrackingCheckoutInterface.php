<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Api;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;

interface TrackingCheckoutInterface
{
    /**
     * REST entry point for checkout tracking payloads.
     *
     * @param string $sessionId
     * @return TrackingCheckoutResponseInterface
     */
    public function execute(string $sessionId): TrackingCheckoutResponseInterface;
}
