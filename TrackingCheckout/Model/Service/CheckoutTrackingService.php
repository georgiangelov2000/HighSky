<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Model\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;
use HighSky\TrackingCheckout\Api\Response\CheckoutTrackingResponseBuilderInterface;
use HighSky\TrackingCheckout\Api\Service\CheckoutTrackingRepositoryInterface;
use HighSky\TrackingCheckout\Api\Service\CheckoutTrackingServiceInterface;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;

class CheckoutTrackingService implements CheckoutTrackingServiceInterface
{
    public function __construct(
        private readonly CheckoutTrackingRepositoryInterface $checkoutTrackingRepository,
        private readonly CheckoutTrackingResponseBuilderInterface $checkoutTrackingResponseBuilder,
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    public function execute(string $sessionId): TrackingCheckoutResponseInterface
    {
        $quote = $this->checkoutTrackingRepository->getBySessionId($sessionId);
        if ($quote === null) {
            throw $this->trackingApiExceptionFactory->notFound(
                sprintf('Checkout session "%s" was not found.', $sessionId)
            );
        }

        return $this->checkoutTrackingResponseBuilder->build($sessionId, $quote);
    }
}
