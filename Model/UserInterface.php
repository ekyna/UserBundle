<?php

namespace Ekyna\Bundle\UserBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\UserInterface as BaseUserInterface;

/**
 * Interface UserInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UserInterface extends BaseUserInterface, IdentityInterface
{
    /**
     * Returns the identifier.
     *
     * @return integer
     */
    public function getId();

    /**
     * Set company
     *
     * @param string $company
     * @return UserInterface|$this
     */
    public function setCompany($company);

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany();

    /**
     * Set phone
     *
     * @param string $phone
     * @return UserInterface|$this
     */
    public function setPhone($phone);

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return UserInterface|$this
     */
    public function setMobile($mobile);

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile();

    /**
     * Set group
     *
     * @param GroupInterface $group
     * @return UserInterface|$this
     */
    public function setGroup(GroupInterface $group = null);

    /**
     * Get group
     *
     * @return GroupInterface
     */
    public function getGroup();

    /**
     * Returns the defaultAddress.
     *
     * @return AddressInterface
     */
    /*public function getDefaultAddress();*/

    /**
     * Sets the defaultAddress.
     *
     * @param AddressInterface $defaultAddress
     * @return UserInterface|$this
     */
    /*public function setDefaultAddress(AddressInterface $defaultAddress = null);*/

    /**
     * Sets the addresses.
     *
     * @param ArrayCollection|AddressInterface[] $addresses
     * @return UserInterface|$this
     */
    public function setAddresses(ArrayCollection $addresses);

    /**
     * Add addresses
     *
     * @param AddressInterface $address
     * @return UserInterface|$this
     */
    public function addAddress(AddressInterface $address);

    /**
     * Remove addresses
     *
     * @param AddressInterface $address
     */
    public function removeAddress(AddressInterface $address);

    /**
     * Returns true whether the group has given address
     *
     * @param AddressInterface $address
     * @return boolean
     */
    public function hasAddress(AddressInterface $address);

    /**
     * Get addresses
     *
     * @return ArrayCollection|AddressInterface[]
     */
    public function getAddresses();

    /**
     * Set created at
     *
     * @param \DateTime $createdAt
     * @return UserInterface|$this
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Get created at
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set update at
     *
     * @param \DateTime $updatedAt
     * @return UserInterface|$this
     */
    public function setUpdatedAt(\DateTime $updatedAt = null);

    /**
     * Get updated at
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Returns the expiresAt.
     *
     * @return \DateTime
     */
    public function getExpiresAt();

    /**
     * Sets whether to send the creation email or not.
     *
     * @param bool $send
     * @return UserInterface|$this
     */
    public function setSendCreationEmail($send);

    /**
     * Returns whether to send the creation email or not.
     *
     * @return bool
     */
    public function getSendCreationEmail();
}
