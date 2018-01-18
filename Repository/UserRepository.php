<?php

namespace Ekyna\Bundle\UserBundle\Repository;

use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepository;

/**
 * Class UserRepository
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository extends ResourceRepository implements UserRepositoryInterface
{
    /**
     * Finds users by role.
     *
     * @param string $role
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface[]
     */
    public function findByRole($role)
    {
        $this->validateRole($role);

        $qb = $this->createQueryBuilder('u');

        return $qb
            ->join('u.group', 'g')
            ->andWhere($qb->expr()->like('g.roles', $qb->expr()->literal('%"' . strtoupper($role) . '"%')))
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds users by roles.
     *
     * @param array $roles
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface[]
     */
    public function findByRoles(array $roles)
    {
        if (empty($roles)) {
            return [];
        }

        $qb = $this->createQueryBuilder('u');
        $qb->join('u.group', 'g');

        $orRoles = $qb->expr()->orX();
        foreach ($roles as $role) {
            $this->validateRole($role);
            $orRoles->add($qb->expr()->like('g.roles', $qb->expr()->literal('%"' . strtoupper($role) . '"%')));
        }

        return $qb
            ->andWhere($orRoles)
            ->getQuery()
            ->getResult();
    }

    /**
     * Validates the given role.
     *
     * @param string $role
     *
     * @throws \InvalidArgumentException
     */
    private function validateRole($role)
    {
        if (!preg_match('~^ROLE_([A-Z_]+)~', $role)) {
            throw new \InvalidArgumentException("Role must start with 'ROLE_'.");
        }
    }
}
