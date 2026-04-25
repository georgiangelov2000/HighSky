<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Response;

use HighSky\Shared\Api\Tracking\Data\TrackingItemInterface;
use HighSky\Shared\Model\Tracking\Data\TrackingItemFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Media\Config as MediaConfig;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class TrackingItemFormatter
{
    /**
     * @var array<int, string|null>
     */
    private array $imageUrlCache = [];

    public function __construct(
        private readonly TrackingItemFactory $trackingItemFactory,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly MediaConfig $mediaConfig
    ) {}

    /**
     * @param OrderItemInterface $item
     * @return TrackingItemInterface
     */
    public function formatOrderItem(OrderItemInterface $item): TrackingItemInterface
    {
        $trackingItem = $this->trackingItemFactory->create();
        $trackingItem->setProductId($item->getProductId() !== null ? (string) $item->getProductId() : null);
        $trackingItem->setSku((string) $item->getSku());
        $trackingItem->setName((string) $item->getName());
        $trackingItem->setQty((float) $item->getQtyOrdered());
        $trackingItem->setImageUrl($this->resolveImageUrl($item->getProductId() !== null ? (int) $item->getProductId() : null));

        return $trackingItem;
    }

    /**
     * @param CartItemInterface $item
     * @return TrackingItemInterface
     */
    public function formatQuoteItem(CartItemInterface $item): TrackingItemInterface
    {
        $trackingItem = $this->trackingItemFactory->create();
        $trackingItem->setProductId($item->getProductId() !== null ? (string) $item->getProductId() : null);
        $trackingItem->setSku((string) $item->getSku());
        $trackingItem->setName((string) $item->getName());
        $trackingItem->setQty((float) $item->getQty());
        $trackingItem->setImageUrl($this->resolveImageUrl($item->getProductId() !== null ? (int) $item->getProductId() : null));

        return $trackingItem;
    }

    private function resolveImageUrl(?int $productId): ?string
    {
        if ($productId === null || $productId <= 0) {
            return null;
        }

        if (array_key_exists($productId, $this->imageUrlCache)) {
            return $this->imageUrlCache[$productId];
        }

        try {
            $product = $this->productRepository->getById($productId, true, 0, true);
        } catch (NoSuchEntityException) {
            return $this->imageUrlCache[$productId] = null;
        }

        $image = (string) $product->getImage();
        if ($image === '' || $image === 'no_selection') {
            return $this->imageUrlCache[$productId] = null;
        }

        return $this->imageUrlCache[$productId] = $this->mediaConfig->getMediaUrl($image);
    }
}
