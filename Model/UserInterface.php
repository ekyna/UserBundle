<?php

namespace Ekyna\Bundle\UserBundle\Model;

use Ekyna\Bundle\CoreBundle\Model\TimestampableInterface;
use FOS\UserBundle\Model\UserInterface as BaseUserInterface;

/**
 * Interface UserInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UserInterface extends BaseUserInterface, IdentityInterface, TimestampableInterface
{
    /**
     * Returns the identifier.
     *
     * @return integer
     */
    public function getId();

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
