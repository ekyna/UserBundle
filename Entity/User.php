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
     * @param \Ekyna\Bundle\UserBundle\Entity\Group $group
     * @return \Ekyna\Bundle\UserBundle\Entity\User
     */
    public function setGroup(Group $group = null)
    {
        $this->group = $group;
    
        return $this;
    }

    /**
     * Get group
     *
     * @return \Ekyna\Bundle\UserBundle\Entity\Group
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
     * Set created at
     *
     * @param \Datetime $createdAt
     * @return \Ekyna\Bundle\UserBundle\Entity\User
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
     * @return \Ekyna\Bundle\UserBundle\Entity\User
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
