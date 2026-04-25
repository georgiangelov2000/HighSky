<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Model\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;
use HighSky\Shared\Model\Tracking\Response\TrackingItemFormatter;
use HighSky\TrackingCheckout\Api\Response\CheckoutTrackingResponseBuilderInterface;
use HighSky\Shared\Model\Tracking\Data\TrackingCheckoutResponseFactory;
use Magento\Quote\Api\Data\CartInterface;

class CheckoutTrackingResponseBuilder implements CheckoutTrackingResponseBuilderInterface
{
    public function __construct(
        private readonly TrackingItemFormatter $trackingItemFormatter,
        private readonly TrackingCheckoutResponseFactory $trackingCheckoutResponseFactory
    ) {}

    public function build(string $sessionId, CartInterface $quote): TrackingCheckoutResponseInterface
    {
        $items = [];
        foreach ($quote->getItems() as $item) {
            if ($item->getParentItemId()) {
                continue;
            }

            $items[] = $this->trackingItemFormatter->formatQuoteItem($item);
        }

        $response = $this->trackingCheckoutResponseFactory->create();
        $response->setSessionId($sessionId);
        $response->setQuoteId((string) $quote->getId());
        $response->setIsActive((bool) $quote->getIsActive());
        $response->setCustomerId($quote->getCustomerId() !== null ? (string) $quote->getCustomerId() : null);
        $response->setCustomerEmail($quote->getCustomerEmail() !== null ? (string) $quote->getCustomerEmail() : null);
        $response->setItemsCount((int) $quote->getItemsCount());
        $response->setItemsQty((float) $quote->getItemsQty());
        $response->setGrandTotal((float) $quote->getGrandTotal());
        $response->setCurrencyCode((string) $quote->getQuoteCurrencyCode());
        $response->setItems($items);

        return $response;
    }
}
