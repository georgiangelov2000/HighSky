<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking;

use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception as WebapiException;

class TrackingApiExceptionFactory
{
    public function badRequest(string $message): WebapiException
    {
        return new WebapiException(new Phrase($message), 0, WebapiException::HTTP_BAD_REQUEST);
    }

    public function unauthorized(string $message): WebapiException
    {
        return new WebapiException(new Phrase($message), 0, WebapiException::HTTP_UNAUTHORIZED);
    }

    public function forbidden(string $message): WebapiException
    {
        return new WebapiException(new Phrase($message), 0, WebapiException::HTTP_FORBIDDEN);
    }

    public function notFound(string $message): WebapiException
    {
        return new WebapiException(new Phrase($message), 0, WebapiException::HTTP_NOT_FOUND);
    }

    public function tooManyRequests(string $message): WebapiException
    {
        return new WebapiException(new Phrase($message), 0, 429);
    }

    public function internalError(
        string $message = 'An internal error occurred while processing the tracking request.'
    ): WebapiException {
        return new WebapiException(new Phrase($message), 0, WebapiException::HTTP_INTERNAL_ERROR);
    }
}
