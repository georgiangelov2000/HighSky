<?php
declare(strict_types=1);

namespace HighSky\Products\Exception\ProductSync;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class InvalidSyncRequestException extends LocalizedException
{
    public function __construct(Phrase $phrase, ?\Exception $cause = null, int $code = 0)
    {
        parent::__construct($phrase, $cause, $code);
    }
}
