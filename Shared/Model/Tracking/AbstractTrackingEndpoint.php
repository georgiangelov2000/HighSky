<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking;

use HighSky\Shared\Api\Tracking\Service\AuthHeaderValidatorInterface;
use HighSky\Shared\Api\Tracking\Validator\TrackingRequestValidatorInterface;
use HighSky\Shared\Model\Security\RequestRateLimiter;
use Magento\Framework\Webapi\Exception as WebapiException;

abstract class AbstractTrackingEndpoint
{
    public function __construct(
        protected readonly Service\TrackingAvailabilityValidator $trackingAvailabilityValidator,
        protected readonly AuthHeaderValidatorInterface $authHeaderValidator,
        protected readonly TrackingRequestValidatorInterface $trackingRequestValidator,
        protected readonly RequestRateLimiter $requestRateLimiter,
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

    protected function enforceRateLimit(string $bucket): void
    {
        $this->requestRateLimiter->validate($bucket);
    }

    /**
     * @template T
     * @param callable(): T $serviceCallback
     * @return T
     */
    protected function executeProtected(string $bucket, callable $serviceCallback)
    {
        return $this->executeWithErrorHandling(function () use ($bucket, $serviceCallback): array {
            $this->trackingAvailabilityValidator->validate();
            $this->enforceRateLimit($bucket);
            $this->authHeaderValidator->validate();

            return ['response' => $serviceCallback()];
        })['response'];
    }
}
