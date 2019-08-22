<?php

namespace Ekyna\Bundle\UserBundle\Service\Provider;

/**
 * Interface UserProviderInterface
 * @package Ekyna\Bundle\UserBundle\Service\Provider
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface UserProviderInterface
{
    /**
     * Returns whether a user is available or not.
     *
     * @return bool
     */
    public function hasUser();

    /**
     * Returns the current user.
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface|null
     */
    public function getUser();

    /**
     * Resets the user provider.
     */
    public function reset();

    /**
     * Clears the user provider.
     */
    public function clear();
}
