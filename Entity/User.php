<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Component\Resource\Model\TimestampableTrait;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Class User
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class User extends BaseUser implements UserInterface
{
    use TimestampableTrait;

    /**
     * @var Group
     */
    protected $group;

    /**
     * @var bool (non mapped)
     */
    protected $sendCreationEmail = true;


    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->username !== $this->email) {
            return sprintf('%s (%s)', $this->username, $this->email);
        }

        return $this->getEmail() ?: 'New user';
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroup(GroupInterface $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup(): ?GroupInterface
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function setSendCreationEmail($send)
    {
        $this->sendCreationEmail = (bool)$send;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSendCreationEmail()
    {
        return $this->sendCreationEmail;
    }
}
