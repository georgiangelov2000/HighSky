<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Model\Repository;

use HighSky\TrackingUsers\Api\Service\UserTrackingRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class UserTrackingRepository implements UserTrackingRepositoryInterface
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CollectionFactory $orderCollectionFactory
    ) {}

    public function getByUserId(int $userId): ?CustomerInterface
    {
        try {
            return $this->customerRepository->getById($userId);
        } catch (NoSuchEntityException) {
            return null;
        }
    }

    public function getPreviousOrdersByUserId(int $userId, int $limit): array
    {
        $collection = $this->orderCollectionFactory->create();
        $collection->addFieldToSelect([
            'entity_id',
            'increment_id',
            'status',
            'created_at',
            'customer_id',
        ]);
        $collection->addFieldToFilter('customer_id', $userId);
        $collection->setOrder('created_at', 'DESC');
        $collection->setPageSize($limit);
        $collection->setCurPage(1);

        return array_values($collection->getItems());
    }
}
