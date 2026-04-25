<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Validator;

interface TrackingRequestValidatorInterface
{
    /**
     * @param string $orderId
     * @return string
     */
    public function validateOrderId(string $orderId): string;

    /**
     * @param string $userId
     * @return int
     */
    public function validateUserId(string $userId): int;

    /**
     * @param string $sessionId
     * @return string
     */
    public function validateSessionId(string $sessionId): string;
}
