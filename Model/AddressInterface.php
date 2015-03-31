<?php

namespace Ekyna\Bundle\UserBundle\Model;

use Ekyna\Bundle\CoreBundle\Model\AddressInterface as BaseInterface;

/**
 * Interface AddressInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface AddressInterface extends BaseInterface, IdentityInterface
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
