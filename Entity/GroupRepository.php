<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\UserBundle\Model\GroupRepositoryInterface;
use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepository;

/**
 * Class GroupRepository
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class GroupRepository extends ResourceRepository implements GroupRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function findDefault()
    {
        if (null === $group = $this->findOneBy(['default' => true])) {
            throw new \RuntimeException('Default user group not found.');
        }

        return $group;
    }

    /**
     * @inheritdoc
     */
    public function findOneByRole($role)
    {
        $qb = $this->createQueryBuilder('g');

        return $qb
            ->andWhere($qb->expr()->like('g.roles', $qb->expr()->literal('%"' . strtoupper($role) . '"%')))
            ->orderBy('g.position', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritdoc
     */
    public function findByRole($role)
    {
        $qb = $this->createQueryBuilder('g');

        return $qb
            ->andWhere($qb->expr()->like('g.roles', $qb->expr()->literal('%"' . strtoupper($role) . '"%')))
            ->orderBy('g.position', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
