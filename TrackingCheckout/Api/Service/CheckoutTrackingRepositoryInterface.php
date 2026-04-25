<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Api\Service;

use Magento\Quote\Api\Data\CartInterface;

interface CheckoutTrackingRepositoryInterface
{
    /**
     * Resolve a quote by tracking session identifier.
     *
     * @param string $sessionId
     * @return CartInterface|null
     */
    public function getBySessionId(string $sessionId): ?CartInterface;
}
