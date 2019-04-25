<?php

namespace Ekyna\Bundle\UserBundle\Repository;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepository;

/**
 * Class UserRepository
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository extends ResourceRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findById(int $id): ?UserInterface
    {
        $qb = $this->createQueryBuilder('u');

        return $qb
            ->select(['u', 'g'])
            ->join('u.group', 'g')
            ->andWhere($qb->expr()->eq('u.id', ':id'))
            ->setMaxResults(1)
            ->getQuery()
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?UserInterface
    {
        $qb = $this->createQueryBuilder('u');

        return $qb
            ->select(['u', 'g'])
            ->join('u.group', 'g')
            ->andWhere($qb->expr()->eq('u.email', ':email'))
            ->setMaxResults(1)
            ->getQuery()
            ->setParameter('email', $email)
            ->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function findByRole(string $role): array
    {
        $this->validateRole($role);

        $qb = $this->createQueryBuilder('u');

        return $qb
            ->join('u.group', 'g')
            ->andWhere($qb->expr()->like('g.roles', $qb->expr()->literal('%"' . $role . '"%')))
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function findByRoles(array $roles): array
    {
        if (empty($roles)) {
            return [];
        }

        $qb = $this->createQueryBuilder('u');
        $qb->join('u.group', 'g');

        $orRoles = $qb->expr()->orX();
        foreach ($roles as $role) {
            $this->validateRole($role);
            $orRoles->add($qb->expr()->like('g.roles', $qb->expr()->literal('%"' . $role . '"%')));
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
    private function validateRole(string $role): void
    {
        if (!preg_match('~^ROLE_([A-Z_]+)~', $role)) {
            throw new \InvalidArgumentException("Role must start with 'ROLE_'.");
        }
    }
}
