<?php
declare(strict_types=1);

namespace HighSky\TrackingCheckout\Model\Repository;

use HighSky\TrackingCheckout\Api\Service\CheckoutTrackingRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

class CheckoutTrackingRepository implements CheckoutTrackingRepositoryInterface
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly QuoteIdMaskFactory $quoteIdMaskFactory
    ) {}

    public function getBySessionId(string $sessionId): ?CartInterface
    {
        if (ctype_digit($sessionId)) {
            try {
                return $this->cartRepository->get((int) $sessionId);
            } catch (NoSuchEntityException) {
            }
        }

        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($sessionId, 'masked_id');
        if (!$quoteIdMask->getQuoteId()) {
            return null;
        }

        try {
            return $this->cartRepository->get((int) $quoteIdMask->getQuoteId());
        } catch (NoSuchEntityException) {
            return null;
        }
    }
}
