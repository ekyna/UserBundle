<?php

namespace Ekyna\Bundle\UserBundle\Repository;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepositoryInterface;

/**
 * Class UserRepositoryInterface
 * @package Ekyna\Bundle\UserBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface UserRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Finds one user by its id.
     *
     * @param int $id
     *
     * @return UserInterface|null
     */
    public function findById(int $id): ?UserInterface;

    /**
     * Finds one user by its email.
     *
     * @param string $email
     *
     * @return UserInterface|null
     */
    public function findByEmail(string $email): ?UserInterface;

    /**
     * Finds the users having the given role.
     *
     * @param string $role
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface[]
     */
    public function findByRole(string $role): array;

    /**
     * Finds the users having at least one of the given roles.
     *
     * @param array $roles
     *
     * @return \Ekyna\Bundle\UserBundle\Model\userInterface[]
     */
    public function findByRoles(array $roles): array;
}