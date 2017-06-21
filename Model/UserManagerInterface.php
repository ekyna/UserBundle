<?php

namespace Ekyna\Bundle\UserBundle\Model;


use FOS\UserBundle\Model\UserInterface as User;
use FOS\UserBundle\Model\UserManagerInterface as FOSUserManagerInterface;

/**
 * Interface UserManagerInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UserManagerInterface extends FOSUserManagerInterface
{
    /**
     * Copy email to username if empty.
     *
     * @param User $user
     */
    public function updateUsername(User $user);

    /**
     * Generates a plain password for the user.
     *
     * @param User $user
     */
    public function generatePassword(User $user);
}
