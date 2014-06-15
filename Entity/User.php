<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class User extends BaseUser implements UserInterface
{
    /**
     * Returns valid gender choices
     * 
     * @return array
     */
    public static function getGenders()
    {
        return array('mr', 'mrs', 'miss');
    }

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $mobile;

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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->addresses = new ArrayCollection();
    }

    /**
     * Returns a string representation
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getEmail();
    }

    /**
     * Returns the search text representation.
     * 
     * @return string
     */
    public function getSearchText()
    {
        return sprintf('%s %s - %s', $this->firstname, $this->lastname, $this->email);
    }

    /**
     * Set company
     *
     * @param string $company
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

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
            $address->setUser($this);
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
