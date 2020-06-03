<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use DateTime;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class LoginToken
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class LoginToken
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTime
     */
    private $expiresAt;


    /**
     * Returns the id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the user.
     *
     * @return UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * Sets the user.
     *
     * @param UserInterface $user
     *
     * @return LoginToken
     */
    public function setUser(UserInterface $user): LoginToken
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Returns the token.
     *
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Sets the token.
     *
     * @param string $token
     *
     * @return LoginToken
     */
    public function setToken(string $token): LoginToken
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns the "expires at".
     *
     * @return DateTime
     */
    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }

    /**
     * Sets the "expires at".
     *
     * @param DateTime $date
     *
     * @return LoginToken
     */
    public function setExpiresAt(DateTime $date): LoginToken
    {
        $this->expiresAt = $date;

        return $this;
    }
}
