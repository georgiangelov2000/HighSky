<?php
declare(strict_types=1);

namespace HighSky\Shared\Controller\Adminhtml\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Config\Model\ConfigFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Cache\TypeListInterface;

class SaveAuthToken extends Action
{
    public const ADMIN_RESOURCE = 'HighSky_Shared::config';

    public function __construct(
        Context $context,
        private readonly ConfigFactory $configFactory,
        private readonly JsonFactory $jsonFactory,
        private readonly TypeListInterface $cacheTypeList
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();

        $token = trim((string) $this->getRequest()->getParam('token'));
        if ($token === '') {
            return $result->setData(['success' => false, 'message' => 'Token cannot be empty.']);
        }

        $websiteCode = $this->getRequest()->getParam('website') ?: null;
        $storeCode = $this->getRequest()->getParam('store') ?: null;

        $configData = $this->configFactory->create([
            'data' => [
                'section' => 'highsky_products',
                'website' => $websiteCode ?? '',
                'store'   => $storeCode ?? '',
                'groups'  => [
                    'tracking_authentication' => [
                        'fields' => [
                            'auth_token' => ['value' => $token],
                        ],
                    ],
                ],
            ],
        ]);

        $configData->save();

        $this->cacheTypeList->cleanType('config');

        return $result->setData(['success' => true]);
    }
}
