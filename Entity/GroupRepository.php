<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;

/**
 * Class GroupRepository
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GroupRepository extends ResourceRepository
{
    /**
     * Returns the default user group.
     *
     * @return GroupInterface
     * @throws \RuntimeException
     */
    public function findDefault()
    {
        if (null === $group = $this->findOneBy(array('default' => true))) {
            throw new \RuntimeException('Default user group not found.');
        }
        return $group;
    }

    /**
     * Finds the lower privilege level group having the given role.
     *
     * @param string $role
     * @return GroupInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByRole($role)
    {
        $qb = $this->createQueryBuilder('g');

        return $qb
            ->andWhere($qb->expr()->like('g.roles', $qb->expr()->literal('%"'.strtoupper($role).'"%')))
            ->orderBy('g.position', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
