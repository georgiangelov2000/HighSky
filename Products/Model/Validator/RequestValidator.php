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
        ?string $status = null,
        ?string $from = null,
        ?string $to = null,
        $limit = null,
        $offset = null
    ): array {
        $normalizedStatus = strtolower(trim((string) $status));
        $this->validateStatus($normalizedStatus);

        $normalizedLimit = $this->normalizeLimit($limit);
        $normalizedOffset = $this->normalizeOffset($offset);
        [$normalizedFrom, $normalizedTo] = $this->normalizeDates($from, $to);

        return [
            'status' => $normalizedStatus,
            'from' => $normalizedFrom,
            'to' => $normalizedTo,
            'limit' => $normalizedLimit,
            'offset' => $normalizedOffset,
        ];
    }

    private function validateStatus(string $status): void
    {
        if (!in_array($status, [SyncConfig::STATUS_NEW, SyncConfig::STATUS_OLD], true)) {
            throw new InvalidSyncRequestException(
                new Phrase('The "status" parameter is required and must be either "new" or "old".')
            );
        }
    }

    private function normalizeLimit($limit): int
    {
        if ($limit === null || $limit === '') {
            return SyncConfig::DEFAULT_LIMIT;
        }

        if (filter_var($limit, FILTER_VALIDATE_INT) === false) {
            throw new InvalidSyncRequestException(
                new Phrase('The "limit" parameter must be an integer.')
            );
        }

        $normalizedLimit = (int) $limit;
        if ($normalizedLimit < SyncConfig::MIN_LIMIT) {
            throw new InvalidSyncRequestException(
                new Phrase('The "limit" parameter must be greater than or equal to %1.', [SyncConfig::MIN_LIMIT])
            );
        }

        return min($normalizedLimit, SyncConfig::MAX_LIMIT);
    }

    private function normalizeOffset($offset): int
    {
        if ($offset === null || $offset === '') {
            return SyncConfig::DEFAULT_OFFSET;
        }

        if (filter_var($offset, FILTER_VALIDATE_INT) === false) {
            throw new InvalidSyncRequestException(
                new Phrase('The "offset" parameter must be an integer.')
            );
        }

        $normalizedOffset = (int) $offset;
        if ($normalizedOffset < SyncConfig::MIN_OFFSET) {
            throw new InvalidSyncRequestException(
                new Phrase('The "offset" parameter must be greater than or equal to %1.', [SyncConfig::MIN_OFFSET])
            );
        }

        return $normalizedOffset;
    }

    /**
     * @return array{0:?string,1:?string}
     */
    private function normalizeDates(?string $from, ?string $to): array
    {
        $hasFrom = $from !== null && trim($from) !== '';
        $hasTo = $to !== null && trim($to) !== '';

        if ($hasFrom xor $hasTo) {
            throw new InvalidSyncRequestException(
                new Phrase('Both "from" and "to" must be provided together, or omit both parameters.')
            );
        }

        if (!$hasFrom && !$hasTo) {
            return [null, null];
        }

        $normalizedFrom = $this->parseDate((string) $from, 'from');
        $normalizedTo = $this->parseDate((string) $to, 'to');

        if ($normalizedFrom >= $normalizedTo) {
            throw new InvalidSyncRequestException(
                new Phrase('The "from" parameter must be earlier than "to".')
            );
        }

        return [
            $normalizedFrom->setTime(0, 0, 0)->format(SyncConfig::DATE_FORMAT),
            $normalizedTo->setTime(23, 59, 59)->format(SyncConfig::DATE_FORMAT),
        ];
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
