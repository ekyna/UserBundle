<?php

namespace Ekyna\Bundle\UserBundle\Model;

/**
 * Interface AddressInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface AddressInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set user
     *
     * @param UserInterface $user
     * @return AddressInterface|$this
     */
    public function setUser(UserInterface $user = null);

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Set company
     *
     * @param string $company
     * @return AddressInterface|$this
     */
    public function setCompany($company);

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany();

    /**
     * Set gender
     *
     * @param integer $gender
     * @return AddressInterface|$this
     */
    public function setGender($gender);

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender();

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return AddressInterface|$this
     */
    public function setFirstName($firstName);

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return AddressInterface|$this
     */
    public function setLastName($lastName);

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName();

    /**
     * Set phone
     *
     * @param string $phone
     * @return AddressInterface|$this
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
     * @return AddressInterface|$this
     */
    public function setMobile($mobile);

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile();

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return AddressInterface|$this
     */
    public function setLocked($locked);

    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return AddressInterface|$this
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return AddressInterface|$this
     */
    public function setUpdatedAt(\DateTime $updatedAt = null);

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();
}
