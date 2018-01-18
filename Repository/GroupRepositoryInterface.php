<?php

namespace Ekyna\Bundle\UserBundle\Repository;

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
     * @return \Ekyna\Bundle\UserBundle\Model\GroupInterface
     *
     * @throws \RuntimeException
     */
    public function findDefault();

    /**
     * Finds the lower privilege level group having the given role.
     *
     * @param string $role
     *
     * @return \Ekyna\Bundle\UserBundle\Model\GroupInterface|null
     */
    public function findOneByRole($role);

    /**
     * Finds the groups having the given role.
     *
     * @param string $role
     *
     * @return \Ekyna\Bundle\UserBundle\Model\GroupInterface[]
     */
    public function findByRole($role);

    /**
     * Finds the groups having at least one of the given roles.
     *
     * @param array $roles
     *
     * @return \Ekyna\Bundle\UserBundle\Model\GroupInterface[]
     */
    public function findByRoles(array $roles);
}
