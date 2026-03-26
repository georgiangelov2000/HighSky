<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Config\Source;

use HighSky\Products\Model\Config\ApiColumnsConfig;
use Magento\Framework\Data\OptionSourceInterface;

class AvailableColumns implements OptionSourceInterface
{
    public function __construct(
        private readonly ApiColumnsConfig $apiColumnsConfig
    ) {}

    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->apiColumnsConfig->getDefaultColumns() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => __($label),
            ];
        }

        return $options;
    }
}
