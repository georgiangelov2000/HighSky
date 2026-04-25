<?php
declare(strict_types=1);

namespace HighSky\Shared\Setup\Patch\Data;

use HighSky\Shared\Model\Config\Tracking\TrackingConfig;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Math\Random;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InitializeDefaultConfig implements DataPatchInterface
{
    private const CONFIG_SCOPE_DEFAULT = 'default';

    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly ConfigInterface $configResource,
        private readonly EncryptorInterface $encryptor,
        private readonly Random $random
    ) {}

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        try {
            if ($this->getCurrentConfigValue(TrackingConfig::XML_PATH_AUTH_TOKEN) === null) {
                $token = sprintf('highsky_%s', $this->random->getRandomString(40));

                $this->configResource->saveConfig(
                    TrackingConfig::XML_PATH_AUTH_TOKEN,
                    $this->encryptor->encrypt($token),
                    self::CONFIG_SCOPE_DEFAULT,
                    0
                );
            }
        } finally {
            $this->moduleDataSetup->getConnection()->endSetup();
        }

        return $this;
    }

    private function getCurrentConfigValue(string $path): ?string
    {
        $connection = $this->moduleDataSetup->getConnection();
        $tableName = $this->moduleDataSetup->getTable('core_config_data');

        $value = $connection->fetchOne(
            $connection->select()
                ->from($tableName, ['value'])
                ->where('scope = ?', self::CONFIG_SCOPE_DEFAULT)
                ->where('scope_id = ?', 0)
                ->where('path = ?', $path)
                ->limit(1)
        );

        if ($value === false || $value === null || trim((string) $value) === '') {
            return null;
        }

        return (string) $value;
    }
}
