<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Security;

use HighSky\Shared\Model\Config\SecurityConfig;
use HighSky\Shared\Model\Tracking\TrackingApiExceptionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Serialize\Serializer\Json;

class RequestRateLimiter
{
    private const WINDOW_SECONDS = 60;
    private const CACHE_KEY_PREFIX = 'highsky_rate_limit_';

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly Json $json,
        private readonly RemoteAddress $remoteAddress,
        private readonly SecurityConfig $securityConfig,
        private readonly TrackingApiExceptionFactory $trackingApiExceptionFactory
    ) {}

    public function validate(string $bucket): void
    {
        if (!$this->securityConfig->isRateLimitEnabled()) {
            return;
        }

        $limit = $this->securityConfig->getRequestsPerMinute();
        if ($limit <= 0) {
            return;
        }

        $cacheKey = $this->buildCacheKey($bucket);
        $now = time();
        $state = $this->loadState($cacheKey, $now);

        if ($state['count'] >= $limit) {
            $retryAfter = max(1, $state['expires_at'] - $now);
            throw $this->trackingApiExceptionFactory->tooManyRequests(
                sprintf('Rate limit exceeded. Retry after %d seconds.', $retryAfter)
            );
        }

        $state['count']++;
        $ttl = max(1, $state['expires_at'] - $now);
        $this->cache->save($this->json->serialize($state), $cacheKey, [], $ttl);
    }

    private function buildCacheKey(string $bucket): string
    {
        $ipAddress = (string) ($this->remoteAddress->getRemoteAddress() ?: 'unknown');

        return self::CACHE_KEY_PREFIX . sha1($bucket . '|' . $ipAddress);
    }

    /**
     * @return array{count:int, expires_at:int}
     */
    private function loadState(string $cacheKey, int $now): array
    {
        $cachedState = $this->cache->load($cacheKey);
        if ($cachedState === false || $cachedState === '') {
            return [
                'count' => 0,
                'expires_at' => $now + self::WINDOW_SECONDS,
            ];
        }

        try {
            $decodedState = $this->json->unserialize($cachedState);
        } catch (\InvalidArgumentException) {
            $decodedState = null;
        }

        if (
            !is_array($decodedState) ||
            !isset($decodedState['count'], $decodedState['expires_at']) ||
            (int) $decodedState['expires_at'] <= $now
        ) {
            return [
                'count' => 0,
                'expires_at' => $now + self::WINDOW_SECONDS,
            ];
        }

        return [
            'count' => max(0, (int) $decodedState['count']),
            'expires_at' => (int) $decodedState['expires_at'],
        ];
    }
}
