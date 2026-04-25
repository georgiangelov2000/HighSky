<?php
declare(strict_types=1);

namespace HighSky\Shared\Controller\Adminhtml\Config;

use HighSky\Shared\Model\Config\Tracking\TrackingConfig;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class GetAuthToken extends Action
{
    public const ADMIN_RESOURCE = 'HighSky_Shared::config';

    public function __construct(
        Context $context,
        private readonly TrackingConfig $trackingConfig,
        private readonly JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        $websiteCode = $this->getRequest()->getParam('website') ?: null;
        $token = $this->trackingConfig->getAuthToken($websiteCode);

        return $result->setData(['token' => $token]);
    }
}
