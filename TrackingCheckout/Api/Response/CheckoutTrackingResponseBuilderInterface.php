<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Api\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;
use Magento\Quote\Api\Data\CartInterface;

interface CheckoutTrackingResponseBuilderInterface
{
    /**
     * Build the public checkout tracking response payload.
     *
     * @param string $sessionId
     * @param CartInterface $quote
     * @return TrackingCheckoutResponseInterface
     */
    public function build(string $sessionId, CartInterface $quote): TrackingCheckoutResponseInterface;
}
