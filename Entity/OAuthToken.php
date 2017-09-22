<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class OAuthToken
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OAuthToken
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     */
    private $owner;

    /**
     * @var string
     */
    private $identifier;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     *
     * @return OAuthToken
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user.
     *
     * @param UserInterface $user
     *
     * @return OAuthToken
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Returns the resource owner.
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Sets the resource owner.
     *
     * @param string $owner
     *
     * @return OAuthToken
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Returns the identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the identifier.
     *
     * @param string $identifier
     *
     * @return OAuthToken
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Returns the access token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the access token.
     *
     * @param string $token
     *
     * @return OAuthToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns the expiration date time.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Sets the expiration date time.
     *
     * @param \DateTime $expiresAt
     *
     * @return OAuthToken
     */
    public function setExpiresAt(\DateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
