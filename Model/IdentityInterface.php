<?php

namespace Ekyna\Bundle\UserBundle\Model;

/**
 * Interface IdentityInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface IdentityInterface
{
    /**
     * Set gender
     *
     * @param string $gender
     * @return IdentityInterface|$this
     */
    public function setGender($gender);

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender();

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return IdentityInterface|$this
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
     * @return IdentityInterface|$this
     */
    public function setLastName($lastName);

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName();
}