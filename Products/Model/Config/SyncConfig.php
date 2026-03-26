<?php
declare(strict_types=1);

namespace HighSky\Products\Model\Config;

class SyncConfig
{
    public const STATUS_NEW = 'new';
    public const STATUS_OLD = 'old';
    public const DATE_FORMAT = 'Y-m-d H:i:s';
    public const DEFAULT_LIMIT = 100;
    public const MAX_LIMIT = 200;
    public const DEFAULT_OFFSET = 0;
    public const MIN_LIMIT = 1;
    public const MIN_OFFSET = 0;
    public const FALLBACK_DAYS = 2;
}
