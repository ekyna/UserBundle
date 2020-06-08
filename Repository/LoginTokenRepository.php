<?php

namespace Ekyna\Bundle\UserBundle\Repository;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Ekyna\Bundle\UserBundle\Entity\LoginToken;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class LoginTokenRepository
 * @package Ekyna\Bundle\UserBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class LoginTokenRepository extends ServiceEntityRepository
{
    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginToken::class);
    }

    /**
     * Finds a valid login token (regarding 'expires at' date) by user.
     *
     * @param UserInterface $user
     *
     * @return LoginToken|null
     */
    public function findOneByUser(UserInterface $user): ?LoginToken
    {
        $qb = $this->createQueryBuilder('t');

        return $qb
            ->andWhere($qb->expr()->eq('t.user', ':user'))
            ->andWhere($qb->expr()->gte('t.expiresAt', ':now'))
            ->andWhere($qb->expr()->eq('DATE(t.createdAt)', 'DATE(:now)'))
            ->setMaxResults(1)
            ->getQuery()
            ->useQueryCache(true)
            ->setParameter('user', $user)
            ->setParameter('now', new DateTime(), Types::DATETIME_MUTABLE)
            ->getOneOrNullResult();
    }

    /**
     * Finds a valid login token (regarding 'expires at' date) by its hash.
     *
     * @param string $hash
     *
     * @return LoginToken|null
     */
    public function findOneByHash(string $hash): ?LoginToken
    {
        $qb = $this->createQueryBuilder('t');

        return $qb
            ->andWhere($qb->expr()->eq('t.token', ':token'))
            ->andWhere($qb->expr()->gte('t.expiresAt', ':now'))
            ->setMaxResults(1)
            ->getQuery()
            ->useQueryCache(true)
            ->setParameter('token', $hash)
            ->setParameter('now', new DateTime(), Types::DATETIME_MUTABLE)
            ->getOneOrNullResult();
    }

    /**
     * Returns the purge query.
     *
     * @return Query
     */
    public function getPurgeQuery(): Query
    {
        $qb = $this->createQueryBuilder('t');

        return $qb
            ->delete(LoginToken::class, 't')
            ->where($qb->expr()->lt('t.expiresAt', ':now'))
            ->getQuery()
            ->useQueryCache(true)
            ->setParameter('now', new DateTime(), Types::DATETIME_MUTABLE);
    }
}
