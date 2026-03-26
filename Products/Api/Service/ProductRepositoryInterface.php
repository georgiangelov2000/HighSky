<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Service;

interface ProductRepositoryInterface
{
    /**
     * Fetch the filtered products page and total count.
     *
     * @param string|null $updateAfter
     * @param int $perPage
     * @param int $currentPage
     * @return array{items: array, total_count: int}
     */
    public function getList(?string $updateAfter, int $perPage, int $currentPage): array;
}
