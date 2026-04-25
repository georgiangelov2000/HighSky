<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Validator;

use HighSky\Shared\Api\Tracking\Validator\TrackingRequestValidatorInterface;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;

class TrackingRequestValidator implements TrackingRequestValidatorInterface
{
    private const MAX_ORDER_ID_LENGTH = 64;
    private const MAX_SESSION_ID_LENGTH = 128;

    public function __construct(
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    public function validateOrderId(string $orderId): string
    {
        $normalizedValue = trim($orderId);
        if ($normalizedValue === '') {
            throw $this->trackingApiExceptionFactory->badRequest(
                'The "order_id" path parameter is required.'
            );
        }

        if (mb_strlen($normalizedValue) > self::MAX_ORDER_ID_LENGTH) {
            throw $this->trackingApiExceptionFactory->badRequest(
                'The "order_id" path parameter is too long.'
            );
        }

        return $normalizedValue;
    }

    public function validateUserId(string $userId): int
    {
        $normalizedValue = trim($userId);
        if ($normalizedValue === '' || filter_var($normalizedValue, FILTER_VALIDATE_INT) === false) {
            throw $this->trackingApiExceptionFactory->badRequest(
                'The "user_id" path parameter must be a positive integer.'
            );
        }

        $normalizedUserId = (int) $normalizedValue;
        if ($normalizedUserId <= 0) {
            throw $this->trackingApiExceptionFactory->badRequest(
                'The "user_id" path parameter must be a positive integer.'
            );
        }

        return $normalizedUserId;
    }

    public function validateSessionId(string $sessionId): string
    {
        $normalizedValue = trim($sessionId);
        if ($normalizedValue === '') {
            throw $this->trackingApiExceptionFactory->badRequest(
                'The "sessionId" path parameter is required.'
            );
        }

        if (mb_strlen($normalizedValue) > self::MAX_SESSION_ID_LENGTH) {
            throw $this->trackingApiExceptionFactory->badRequest(
                'The "sessionId" path parameter is too long.'
            );
        }

        return $normalizedValue;
    }
}
