<?php
declare(strict_types=1);

namespace HighSky\Shared\Api\Tracking\Data;

interface TrackingUserResponseInterface
{
    public const USER_NAME = 'user_name';
    public const USER_EMAIL = 'user_email';
    public const USER_TELEPHONE = 'user_telephone';
    public const PREVIOUS_ORDERS = 'previous_orders';

    /**
     * @return string
     */
    public function getUserName(): string;

    /**
     * @param string $userName
     * @return $this
     */
    public function setUserName(string $userName): self;

    /**
     * @return string
     */
    public function getUserEmail(): string;

    /**
     * @param string $userEmail
     * @return $this
     */
    public function setUserEmail(string $userEmail): self;

    /**
     * @return string|null
     */
    public function getUserTelephone(): ?string;

    /**
     * @param string|null $userTelephone
     * @return $this
     */
    public function setUserTelephone(?string $userTelephone): self;

    /**
     * @return \HighSky\Shared\Api\Tracking\Data\TrackingHistoricalOrderInterface[]
     */
    public function getPreviousOrders();

    /**
     * @param \HighSky\Shared\Api\Tracking\Data\TrackingHistoricalOrderInterface[] $previousOrders
     * @return $this
     */
    public function setPreviousOrders(array $previousOrders): self;
}
