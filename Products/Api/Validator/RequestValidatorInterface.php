<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Validator;

interface RequestValidatorInterface
{
    /**
     * Validate and normalize request parameters.
     *
     * @param string|null $status
     * @param string|null $from
     * @param string|null $to
     * @param int|string|null $limit
     * @param int|string|null $offset
     * @return array{status:string,from:?string,to:?string,limit:int,offset:int}
     */
    public function validate(
        ?string $status = null,
        ?string $from = null,
        ?string $to = null,
        $limit = null,
        $offset = null
    ): array;
}
