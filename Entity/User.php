<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\UserBundle\Model\AddressInterface;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Class User
 * @package Ekyna\Bundle\UserBundle\Entity
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
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

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
     * @var AddressInterface
     */
    /*protected $defaultAddress;*/

    /**
     * @var ArrayCollection|AddressInterface[]
     */
    protected $addresses;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
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
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;
        if (empty($this->username)) {
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
     * Returns a string representation
     * 
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s %s - %s', $this->firstName, $this->lastName, $this->email);
    }

    /**
     * {@inheritdoc}
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * {@inheritdoc}
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * {@inheritdoc}
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMobile()
    {
        return $this->mobile;
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
        if($this->group instanceof Group) {
            return array_merge(
                array(self::ROLE_DEFAULT, $this->group->getSecurityIdentity()->getRole()),
                $this->group->getRoles()
            );
        }
        return array(self::ROLE_DEFAULT);
    }

    /**
     * {@inheritdoc}
     */
    /*public function getDefaultAddress()
    {
        return $this->defaultAddress;
    }*/

    /**
     * {@inheritdoc}
     */
    /*public function setDefaultAddress(AddressInterface $defaultAddress = null)
    {
        $this->defaultAddress = $defaultAddress;
        return $this;
    }*/

    /**
     * {@inheritdoc}
     */
    public function setAddresses(ArrayCollection $addresses)
    {
        /** @var AddressInterface $address */
        foreach($addresses as $address) {
            $address->setUser($this);
        }
        $this->addresses = $addresses;
    }

    /**
     * {@inheritdoc}
     */
    public function addAddress(AddressInterface $address)
    {
        if(!$this->hasAddress($address)) {
            $address->setUser($this);
            $this->addresses->add($address);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAddress(AddressInterface $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * {@inheritdoc}
     */
    public function hasAddress(AddressInterface $address)
    {
        return $this->addresses->contains($address);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Returns the expiresAt.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
}
