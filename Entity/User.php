<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * User
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var ArrayCollection
     */
    protected $addresses;

    /**
     * @var \Datetime
     */
    protected $createdAt;

    /**
     * @var \Datetime
     */
    protected $updatedAt;

    /**
     * Set group
     *
     * @param Group $group
     * @return User
     */
    public function setGroup(Group $group = null)
    {
        $this->group = $group;
    
        return $this;
    }

    /**
     * Get group
     *
     * @return Group
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
        if($this->group instanceof Group) {
            return array_merge(
                array(self::ROLE_DEFAULT, $this->group->getSecurityIdentity()->getRole()),
                $this->group->getRoles()
            );
        }
        return array(self::ROLE_DEFAULT);
    }

    /**
     * Add addresses
     *
     * @param Address $address
     * @return Group
     */
    public function addAddress(Address $address)
    {
        if(!$this->hasAddress($address)) {
            $address->setGroup($this);
            $this->addresses->add($address);
        }
        return $this;
    }

    /**
     * Remove addresses
     *
     * @param Address $address
     */
    public function removeAddress(Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Returns true whether the group has given address
     *
     * @param Address $address
     * @return boolean
     */
    public function hasAddress(Address $address)
    {
        return $this->addresses->contains($address);
    }

    /**
     * Get addresses
     *
     * @return ArrayCollection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Set created at
     *
     * @param \Datetime $createdAt
     * @return User
     */
    public function setCreatedAt(\Datetime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get created at
     *
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set update at
     *
     * @param \Datetime $updatedAt
     * @return User
     */
    public function setUpdatedAt(\Datetime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updated at
     *
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
