<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Webapi;

use HighSky\Products\Api\Data\ProductSyncItemInterface;
use HighSky\Products\Model\Config\ApiColumnsConfig;

class FilterProductOutput
{
    public function __construct(
        private readonly ApiColumnsConfig $apiColumnsConfig
    ) {}

    /**
     * Remove disabled columns from the serialized API item output.
     *
     * @param ProductSyncItemInterface $dataObject
     * @param array<string, mixed> $result
     * @return array<string, mixed>
     */
    public function execute(
        ProductSyncItemInterface $dataObject,
        array $result
    ): array {
        unset($dataObject);

        $allowedColumns = array_merge(
            $this->apiColumnsConfig->getEnabledColumns(),
            $this->apiColumnsConfig->getExtraAttributeCodes()
        );

        if ($allowedColumns === []) {
            return $result;
        }

        $allowedColumns = array_flip($allowedColumns);

        return array_filter(
            $result,
            static fn (string $key): bool => isset($allowedColumns[$key]),
            ARRAY_FILTER_USE_KEY
        );
    }
}
