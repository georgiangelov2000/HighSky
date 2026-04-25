<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Model\Service;

use HighSky\Shared\Api\Tracking\Data\TrackingUserResponseInterface;
use HighSky\TrackingUsers\Api\Response\UserTrackingResponseBuilderInterface;
use HighSky\TrackingUsers\Api\Service\UserTrackingRepositoryInterface;
use HighSky\TrackingUsers\Api\Service\UserTrackingServiceInterface;
use HighSky\Shared\Model\Config\Tracking\TrackingConfig;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;

class UserTrackingService implements UserTrackingServiceInterface
{
    public function __construct(
        private readonly UserTrackingRepositoryInterface $userTrackingRepository,
        private readonly UserTrackingResponseBuilderInterface $userTrackingResponseBuilder,
        private readonly TrackingConfig $trackingConfig,
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    public function execute(int $userId): TrackingUserResponseInterface
    {
        $customer = $this->userTrackingRepository->getByUserId($userId);
        if ($customer === null) {
            throw $this->trackingApiExceptionFactory->notFound(
                sprintf('User "%d" was not found.', $userId)
            );
        }

        $orders = $this->userTrackingRepository->getPreviousOrdersByUserId(
            $userId,
            $this->trackingConfig->getUserOrderHistoryLimit()
        );

        return $this->userTrackingResponseBuilder->build($customer, $orders);
    }
}
