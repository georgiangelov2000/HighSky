<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Api\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingUserResponseInterface;
use Magento\Customer\Api\Data\CustomerInterface;

interface UserTrackingResponseBuilderInterface
{
    /**
     * Build the public user tracking response payload.
     *
     * @param CustomerInterface $customer
     * @param array $orders
     * @return TrackingUserResponseInterface
     */
    public function build(CustomerInterface $customer, array $orders): TrackingUserResponseInterface;
}
