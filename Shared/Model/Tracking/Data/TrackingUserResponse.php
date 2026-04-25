<?php
declare(strict_types=1);

namespace HighSky\Shared\Model\Tracking\Data;

use HighSky\Shared\Api\Tracking\Data\TrackingUserResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class TrackingUserResponse extends AbstractSimpleObject implements TrackingUserResponseInterface
{
    public function getUserName(): string
    {
        return (string) $this->_get(self::USER_NAME);
    }

    public function setUserName(string $userName): TrackingUserResponseInterface
    {
        return $this->setData(self::USER_NAME, $userName);
    }

    public function getUserEmail(): string
    {
        return (string) $this->_get(self::USER_EMAIL);
    }

    public function setUserEmail(string $userEmail): TrackingUserResponseInterface
    {
        return $this->setData(self::USER_EMAIL, $userEmail);
    }

    public function getUserTelephone(): ?string
    {
        $telephone = $this->_get(self::USER_TELEPHONE);

        return $telephone !== null ? (string) $telephone : null;
    }

    public function setUserTelephone(?string $userTelephone): TrackingUserResponseInterface
    {
        return $this->setData(self::USER_TELEPHONE, $userTelephone);
    }

    public function getPreviousOrders()
    {
        return $this->_get(self::PREVIOUS_ORDERS) ?? [];
    }

    public function setPreviousOrders(array $previousOrders): TrackingUserResponseInterface
    {
        return $this->setData(self::PREVIOUS_ORDERS, $previousOrders);
    }
}
