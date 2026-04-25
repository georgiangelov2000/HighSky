<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Model\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingCheckoutResponseInterface;
use HighSky\Shared\Model\Tracking\Response\TrackingItemFormatter;
use HighSky\TrackingCheckout\Api\Response\CheckoutTrackingResponseBuilderInterface;
use HighSky\Shared\Model\Tracking\Data\TrackingCheckoutResponseFactory;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class CheckoutTrackingResponseBuilder implements CheckoutTrackingResponseBuilderInterface
{
    public function __construct(
        private readonly TrackingItemFormatter $trackingItemFormatter,
        private readonly TrackingCheckoutResponseFactory $trackingCheckoutResponseFactory
    ) {}

    public function build(string $sessionId, CartInterface $quote): TrackingCheckoutResponseInterface
    {
        // CartRepository always returns a Quote instance. Narrowing here makes the
        // concrete Quote methods available (getAllItems, getCustomerId, etc.) which are
        // not declared on CartInterface but exist on the model via magic __call.
        if (!$quote instanceof Quote) {
            throw new \InvalidArgumentException('Expected ' . Quote::class . ', got ' . get_class($quote));
        }

        $items = [];
        /** @var QuoteItem $item */
        foreach ($quote->getAllItems() as $item) {
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
