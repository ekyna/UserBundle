<?php

namespace Ekyna\Bundle\UserBundle\Model;

use FOS\UserBundle\Model\UserInterface as BaseUserInterface;

/**
 * Interface UserInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UserInterface extends BaseUserInterface
{

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
}