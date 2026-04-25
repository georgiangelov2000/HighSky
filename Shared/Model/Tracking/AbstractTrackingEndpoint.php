<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking;

use HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface;
use HighSky\Shared\Api\Tracking\Validator\TrackingRequestValidatorInterface;
use Magento\Framework\Webapi\Exception as WebapiException;

abstract class AbstractTrackingEndpoint
{
    public function __construct(
        protected readonly Service\TrackingAvailabilityValidator $trackingAvailabilityValidator,
        protected readonly AuthHeaderValidatorInterface $authHeaderValidator,
        protected readonly TrackingRequestValidatorInterface $trackingRequestValidator,
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    /**
     * @param callable(): array<string, mixed> $callback
     * @return array<string, mixed>
     */
    protected function executeWithErrorHandling(callable $callback): array
    {
        try {
            return $callback();
        } catch (WebapiException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw $this->trackingApiExceptionFactory->internalError();
        }
    }
}
