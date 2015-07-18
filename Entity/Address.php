<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\CoreBundle\Entity\AbstractAddress;
use Ekyna\Bundle\CoreBundle\Model\TimestampableTrait;
use Ekyna\Bundle\UserBundle\Model\AddressInterface;
use Ekyna\Bundle\UserBundle\Model\IdentityTrait;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class Address
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Address extends AbstractAddress implements AddressInterface
{
    use IdentityTrait;
    use TimestampableTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $mobile;

    /**
     * @var boolean
     */
    protected $locked;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->locked = false;
    }

    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s, %s', $this->getStreet(), $this->getCity());
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
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
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocked()
    {
        return $this->locked;
    }
}
