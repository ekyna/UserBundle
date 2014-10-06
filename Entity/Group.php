<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\Group as BaseGroup;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;

/**
 * Class Group
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Group extends BaseGroup implements GroupInterface
{
    /**
     * @var boolean
     */
    protected $default = false;

    /**
     * @var ArrayCollection
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct($name = null, array $roles = array())
    {
        parent::__construct($name, $roles);
        $this->default = false;
        $this->users = new ArrayCollection();
    }

    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * Set whether the group is the default one
     *  
     * @param boolean $default
     * @return Group
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Get whether the group is the default one
     * 
     * @return boolean
     */
    public function getdefault()
    {
        return $this->default;
    }

    /**
     * Add users
     *
     * @param User $user
     * @return Group
     */
    public function addUser(User $user)
    {
        if(!$this->hasUser($user)) {
            $user->setGroup($this);
            $this->users->add($user);
        }
        return $this;
    }

    /**
     * Remove users
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Returns true whether the group has given user
     *
     * @param User $user
     * @return boolean
     */
    public function hasUser(User $user)
    {
        return $this->users->contains($user);
    }

    /**
     * Get users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurityIdentity()
    {
        return new RoleSecurityIdentity(sprintf('ROLE_GROUP_%d', $this->getId()));
    }
}
