<?php
declare(strict_types=1);

namespace HighSky\TrackingOrders\Model\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingOrderResponseInterface;
use HighSky\Shared\Model\Tracking\Response\TrackingItemFormatter;
use HighSky\TrackingOrders\Api\Response\OrderTrackingResponseBuilderInterface;
use HighSky\Shared\Model\Tracking\Data\TrackingOrderResponseFactory;
use Magento\Sales\Api\Data\OrderInterface;

class OrderTrackingResponseBuilder implements OrderTrackingResponseBuilderInterface
{
    public function __construct(
        private readonly TrackingItemFormatter $trackingItemFormatter,
        private readonly TrackingOrderResponseFactory $trackingOrderResponseFactory
    ) {}

    public function build(OrderInterface $order): TrackingOrderResponseInterface
    {
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $items = [];

        foreach ($order->getItems() as $item) {
            if ($item->getParentItemId()) {
                continue;
            }

            $items[] = $this->trackingItemFormatter->formatOrderItem($item);
        }

        $response = $this->trackingOrderResponseFactory->create();
        $response->setOrderId((string) $order->getIncrementId());
        $response->setStatus((string) $order->getStatus());
        $response->setCreatedAt((string) $order->getCreatedAt());
        $response->setUserId($order->getCustomerId() !== null ? (string) $order->getCustomerId() : null);
        $response->setUserName(trim(sprintf(
            '%s %s',
            (string) $order->getCustomerFirstname(),
            (string) $order->getCustomerLastname()
        )) ?: null);
        $response->setUserEmail($order->getCustomerEmail() !== null ? (string) $order->getCustomerEmail() : null);
        $response->setUserTelephone($billingAddress?->getTelephone() ?: $shippingAddress?->getTelephone());
        $response->setGrandTotal((float) $order->getGrandTotal());
        $response->setCurrencyCode((string) $order->getOrderCurrencyCode());
        $response->setItems($items);

        return $response;
    }
}
