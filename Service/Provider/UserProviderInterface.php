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
     * Returns the current user.
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface|null
     */
    public function getUser();
}
