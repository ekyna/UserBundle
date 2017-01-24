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
    public function __toString()
    {
        return $this->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;
        if ($this->username != $email) {
            $this->username = $email;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
        if (empty($this->usernameCanonical)) {
            $this->usernameCanonical = $emailCanonical;
        }

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
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        if ($this->group instanceof Group) {
            return array_merge(
                [self::ROLE_DEFAULT, $this->group->getSecurityIdentity()->getRole()],
                $this->group->getRoles()
            );
        }

        return [self::ROLE_DEFAULT];
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        throw new \BadMethodCallException('Please use the AuthorizationChecker service.');
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
