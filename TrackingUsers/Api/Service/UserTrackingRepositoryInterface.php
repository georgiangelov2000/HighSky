<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Api\Service;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Sales\Api\Data\OrderInterface;

interface UserTrackingRepositoryInterface
{
    /**
     * Resolve a Magento customer by id.
     *
     * @param int $userId
     * @return CustomerInterface|null
     */
    public function getByUserId(int $userId): ?CustomerInterface;

    /**
     * Fetch historical orders for a customer.
     *
     * @param int $userId
     * @param int $limit
     * @return OrderInterface[]
     */
    public function getPreviousOrdersByUserId(int $userId, int $limit): array;
}
