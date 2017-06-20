<?php

namespace Ekyna\Bundle\UserBundle\Model;

/**
 * Interface GroupRepositoryInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface GroupRepositoryInterface
{
    /**
     * Returns the default user group.
     *
     * @return GroupInterface
     * @throws \RuntimeException
     */
    public function findDefault();

    /**
     * Finds the lower privilege level group having the given role.
     *
     * @param string $role
     *
     * @return GroupInterface|null
     */
    public function findOneByRole($role);

    /**
     * Finds the groups having the given role.
     *
     * @param string $role
     *
     * @return GroupInterface[]
     */
    public function findByRole($role);

    /**
     * Finds the groups having at least one of the given roles.
     *
     * @param string|string[] $roles
     *
     * @return GroupInterface[]
     */
    public function findByRoles($roles);
}
