<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Service;

use HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface;
use HighSky\Shared\Model\Config\Tracking\TrackingConfig;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;
use Magento\Framework\Webapi\Rest\Request;

class AuthHeaderValidator implements AuthHeaderValidatorInterface
{
    private const AUTH_HEADER = 'X-SkyCommerce-Auth';

    public function __construct(
        private readonly Request $request,
        private readonly TrackingConfig $trackingConfig,
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    public function validate(): void
    {
        if (!$this->trackingConfig->isAuthRequired()) {
            return;
        }

        $headerValue = trim((string) $this->request->getHeader(self::AUTH_HEADER));
        if ($headerValue === '') {
            throw $this->trackingApiExceptionFactory->unauthorized(
                'Missing X-SkyCommerce-Auth header.'
            );
        }

        $configuredToken = $this->trackingConfig->getAuthToken();
        if ($configuredToken === '') {
            throw $this->trackingApiExceptionFactory->unauthorized(
                'Tracking API authentication is not configured.'
            );
        }

        if (!hash_equals($configuredToken, $headerValue)) {
            throw $this->trackingApiExceptionFactory->unauthorized(
                'Invalid X-SkyCommerce-Auth header.'
            );
        }
    }
}
