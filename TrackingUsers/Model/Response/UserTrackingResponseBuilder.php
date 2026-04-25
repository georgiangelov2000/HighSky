<?php
declare(strict_types=1);

namespace HighSky\TrackingUsers\Model\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingUserResponseInterface;
use HighSky\Shared\Model\Tracking\Response\TrackingItemFormatter;
use HighSky\TrackingUsers\Api\Response\UserTrackingResponseBuilderInterface;
use HighSky\Shared\Model\Tracking\Data\TrackingHistoricalOrderFactory;
use HighSky\Shared\Model\Tracking\Data\TrackingUserResponseFactory;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;

class UserTrackingResponseBuilder implements UserTrackingResponseBuilderInterface
{
    public function __construct(
        private readonly TrackingItemFormatter $trackingItemFormatter,
        private readonly TrackingHistoricalOrderFactory $trackingHistoricalOrderFactory,
        private readonly TrackingUserResponseFactory $trackingUserResponseFactory
    ) {}

    public function build(CustomerInterface $customer, array $orders): TrackingUserResponseInterface
    {
        $formattedOrders = [];
        foreach ($orders as $order) {
            $items = [];
            foreach ($order->getItems() as $item) {
                if ($item->getParentItemId()) {
                    continue;
                }

                $items[] = $this->trackingItemFormatter->formatOrderItem($item);
            }

            $formattedOrder = $this->trackingHistoricalOrderFactory->create();
            $formattedOrder->setOrderId((string) $order->getIncrementId());
            $formattedOrder->setStatus((string) $order->getStatus());
            $formattedOrder->setCreatedAt((string) $order->getCreatedAt());
            $formattedOrder->setItems($items);
            $formattedOrders[] = $formattedOrder;
        }

        $response = $this->trackingUserResponseFactory->create();
        $response->setUserName(trim(sprintf(
            '%s %s',
            (string) $customer->getFirstname(),
            (string) $customer->getLastname()
        )));
        $response->setUserEmail((string) $customer->getEmail());
        $response->setUserTelephone($this->resolveTelephone($customer));
        $response->setPreviousOrders($formattedOrders);

        return $response;
    }

    private function resolveTelephone(CustomerInterface $customer): ?string
    {
        $addresses = $customer->getAddresses() ?? [];
        $preferredIds = array_filter([
            $customer->getDefaultBilling(),
            $customer->getDefaultShipping(),
        ]);

        foreach ($preferredIds as $preferredId) {
            foreach ($addresses as $address) {
                if ((string) $address->getId() === (string) $preferredId && $address->getTelephone()) {
                    return (string) $address->getTelephone();
                }
            }
        }

        foreach ($addresses as $address) {
            if ($address instanceof AddressInterface && $address->getTelephone()) {
                return (string) $address->getTelephone();
            }
        }

        return null;
    }
}
