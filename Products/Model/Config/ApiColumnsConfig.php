<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ApiColumnsConfig
{
    private const XML_PATH_ENABLED_COLUMNS = 'highsky_products/api_columns/enabled_columns';
    private const XML_PATH_EXTRA_ATTRIBUTE_CODES = 'highsky_products/api_columns/extra_attribute_codes';

    /**
     * @param array<string, string> $defaultColumns
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly array $defaultColumns = []
    ) {}

    /**
     * @return string[]
     */
    public function getEnabledColumns(?int $storeId = null): array
    {
        $configured = (string) $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED_COLUMNS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $columns = $this->explodeCsv($configured);
        if ($columns === []) {
            $columns = array_keys($this->defaultColumns);
        }

        return array_values(array_unique($columns));
    }

    /**
     * @return string[]
     */
    public function getExtraAttributeCodes(?int $storeId = null): array
    {
        $configured = (string) $this->scopeConfig->getValue(
            self::XML_PATH_EXTRA_ATTRIBUTE_CODES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $codes = [];
        foreach ($this->explodeCsv($configured) as $code) {
            $normalized = strtolower(trim($code));
            if ($normalized !== '' && preg_match('/^[a-z][a-z0-9_]*$/', $normalized)) {
                $codes[] = $normalized;
            }
        }

        return array_values(array_unique($codes));
    }

    /**
     * @return array<string, string>
     */
    public function getDefaultColumns(): array
    {
        return $this->defaultColumns;
    }

    /**
     * @return string[]
     */
    private function explodeCsv(string $value): array
    {
        if (trim($value) === '') {
            return [];
        }

        return array_values(
            array_filter(
                array_map('trim', explode(',', $value)),
                static fn (string $item): bool => $item !== ''
            )
        );
    }
}
