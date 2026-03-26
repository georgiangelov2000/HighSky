<?php
declare(strict_types=1);

namespace HighSky\Products\Api\Validator;

interface RequestValidatorInterface
{
    /**
     * Validate and normalize request parameters.
     *
     * @param int|string|null $perPage
     * @param string|null $updateAfter
     * @return array{per_page:int,update_after:?string,current_page:int}
     */
    public function validate(
        $perPage = null,
        ?string $updateAfter = null
    ): array;
}
