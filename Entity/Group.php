<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Group
 */
class Group extends BaseGroup implements GroupInterface
{
    /**
     * @var boolean
     */
    protected $default = false;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct($name, array $roles = array())
    {
        parent::__construct($name, $roles);
        $this->default = false;
        $this->users = new ArrayCollection();
    }

    /**
     * Set whether the group is the default one
     *  
     * @param boolean $default
     * @return \Ekyna\Bundle\UserBundle\Entity\Group
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
     * @param \Ekyna\Bundle\UserBundle\Entity\User $user
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
     * @param \Ekyna\Bundle\UserBundle\Entity\User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Returns true whether the group has given user
     *
     * @param \Ekyna\Bundle\UserBundle\Entity\User $user
     * @return boolean
     */
    public function hasUser(User $user)
    {
        return $this->users->contains($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
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
