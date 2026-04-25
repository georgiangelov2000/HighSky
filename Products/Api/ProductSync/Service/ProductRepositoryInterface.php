<?php
declare(strict_types=1);

namespace HighSky\Products\Api\ProductSync\Service;

interface ProductRepositoryInterface
{
    /**
     * Fetch the filtered products page and total count.
     *
     * @param string|null $updateAfter
     * @param int $perPage
     * @param int $currentPage
     * @param string[] $enabledColumns
     * @return array{items: array, total_count: int}
     */
    public function getList(?string $updateAfter, int $perPage, int $currentPage, array $enabledColumns): array;
}
