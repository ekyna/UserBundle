<?php

namespace Ekyna\Bundle\UserBundle\Model;

use FOS\UserBundle\Model\UserManagerInterface as FOSUserManagerInterface;

/**
 * Interface UserManagerInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UserManagerInterface extends FOSUserManagerInterface
{
    /**
     * Generates a plain password for the user.
     *
     * @param UserInterface $user
     */
    public function generatePassword(UserInterface $user);
}