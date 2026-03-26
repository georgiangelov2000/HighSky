<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Service;

interface ProductRepositoryInterface
{
    /**
     * Fetch one page of products plus one extra row to detect whether more data exists.
     *
     * @param string $status
     * @param string $from
     * @param string $to
     * @param int $limit
     * @param int $offset
     * @return array{items: array, has_more: bool}
     */
    public function getList(string $status, string $from, string $to, int $limit, int $offset): array;
}
