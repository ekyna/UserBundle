<?php

namespace Ekyna\Bundle\UserBundle\Repository;

use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepositoryInterface;

/**
 * Class UserRepositoryInterface
 * @package Ekyna\Bundle\UserBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface UserRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Finds the users having the given role.
     *
     * @param string $role
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface[]
     */
    public function findByRole($role);

    /**
     * Finds the users having at least one of the given roles.
     *
     * @param array $roles
     *
     * @return \Ekyna\Bundle\UserBundle\Model\userInterface[]
     */
    public function findByRoles(array $roles);
}