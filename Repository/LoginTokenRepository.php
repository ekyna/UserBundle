<?php

namespace Ekyna\Bundle\UserBundle\Repository;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Ekyna\Bundle\UserBundle\Entity\LoginToken;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Service\Security\LoginTokenManager;

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
     * Finds the login token by user.
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
            ->andWhere($qb->expr()->gte('t.expiresAt', ':expires'))
            ->andWhere($qb->expr()->eq('DATE(t.createdAt)', 'DATE(:today)'))
            ->setMaxResults(1)
            ->getQuery()
            ->useQueryCache(true)
            ->setParameter('user', $user)
            ->setParameter('expires', new DateTime(LoginTokenManager::TOKEN_EXPIRES), Types::DATETIME_MUTABLE)
            ->setParameter('today', new DateTime(), Types::DATETIME_MUTABLE)
            ->getOneOrNullResult();
    }

    /**
     * Finds the login token by its hash.
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
            ->andWhere($qb->expr()->gte('t.expiresAt', ':expires'))
            ->setMaxResults(1)
            ->getQuery()
            ->useQueryCache(true)
            ->setParameter('token', $hash)
            ->setParameter('expires', new DateTime(LoginTokenManager::TOKEN_EXPIRES), Types::DATETIME_MUTABLE)
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
