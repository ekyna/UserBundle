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
     * Get user
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany();

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender();

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname();

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname();

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet();

    /**
     * Get supplement
     *
     * @return string
     */
    public function getSupplement();

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode();

    /**
     * Get city
     *
     * @return string
     */
    public function getCity();

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile();

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();
}
