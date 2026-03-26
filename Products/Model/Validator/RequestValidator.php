<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Validator;

use HighSky\Products\Api\Validator\RequestValidatorInterface;
use HighSky\Products\Exception\InvalidSyncRequestException;
use HighSky\Products\Model\Config\SyncConfig;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class RequestValidator implements RequestValidatorInterface
{
    public function __construct(
        private readonly TimezoneInterface $timezone
    ) {}

    public function validate(
        $perPage = null,
        ?string $updateAfter = null
    ): array {
        return [
            'per_page' => $this->normalizePerPage($perPage),
            'update_after' => $this->normalizeUpdateAfter($updateAfter),
            'current_page' => SyncConfig::DEFAULT_PAGE,
        ];
    }

    private function normalizePerPage($perPage): int
    {
        if ($perPage === null || $perPage === '') {
            return SyncConfig::DEFAULT_PER_PAGE;
        }

        if (filter_var($perPage, FILTER_VALIDATE_INT) === false) {
            throw new InvalidSyncRequestException(
                new Phrase('The "per_page" parameter must be an integer.')
            );
        }

        $normalizedPerPage = (int) $perPage;
        if ($normalizedPerPage < SyncConfig::MIN_PER_PAGE) {
            throw new InvalidSyncRequestException(
                new Phrase(
                    'The "per_page" parameter must be greater than or equal to %1.',
                    [SyncConfig::MIN_PER_PAGE]
                )
            );
        }

        return min($normalizedPerPage, SyncConfig::MAX_PER_PAGE);
    }

    private function normalizeUpdateAfter(?string $updateAfter): ?string
    {
        if ($updateAfter === null || trim($updateAfter) === '') {
            return null;
        }

        return $this->parseDate($updateAfter, 'update_after')->format(SyncConfig::DATE_FORMAT);
    }

    private function parseDate(string $value, string $fieldName): \DateTimeImmutable
    {
        $timezone = new \DateTimeZone((string) $this->timezone->getConfigTimezone());
        $trimmedValue = trim($value);
        $date = \DateTimeImmutable::createFromFormat(SyncConfig::DATE_FORMAT, $trimmedValue, $timezone);
        $errors = \DateTimeImmutable::getLastErrors();

        if (
            $date === false ||
            ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) ||
            $date->format(SyncConfig::DATE_FORMAT) !== $trimmedValue
        ) {
            throw new InvalidSyncRequestException(
                new Phrase(
                    'The "%1" parameter must use the format %2.',
                    [$fieldName, SyncConfig::DATE_FORMAT]
                )
            );
        }

        return $date;
    }
}
