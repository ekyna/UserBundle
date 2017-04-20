<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Repository;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Ekyna\Bundle\UserBundle\Entity\Token;
use Ekyna\Bundle\UserBundle\Model\TokenTrait;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class TokenRepository
 * @package Ekyna\Bundle\UserBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class TokenRepository extends ServiceEntityRepository
{
    use TokenTrait;


    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    /**
     * Finds token by user and type.
     *
     * @param UserInterface $user
     * @param string        $type
     * @param bool          $valid
     *
     * @return Token|null
     */
    public function findOneByUserAndType(UserInterface $user, string $type, bool $valid = true): ?Token
    {
        Token::validateType($type);

        $qb = $this->createQueryBuilder('t');

        $parameters = [
            'user' => $user,
            'type' => $type,
        ];

        if ($valid) {
            $qb->andWhere($qb->expr()->gte('t.expiresAt', ':now'));
            $parameters['now'] = new DateTime();
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return $qb
            ->andWhere($qb->expr()->eq('t.user', ':user'))
            ->andWhere($qb->expr()->eq('t.type', ':type'))
            ->setMaxResults(1)
            ->getQuery()
            ->useQueryCache(true)
            ->setParameters($parameters)
            ->getOneOrNullResult();
    }

    /**
     * Finds a valid token (regarding 'expires at' date) by its hash.
     *
     * @param string      $hash
     * @param string|null $type
     * @param bool        $user
     *
     * @return Token|null
     */
    public function findOneByHash(string $hash, ?string $type, bool $user = true): ?Token
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->andWhere($qb->expr()->eq('t.hash', ':hash'))
            ->andWhere($qb->expr()->gte('t.expiresAt', ':now'));

        $parameters = [
            'hash' => $hash,
            'now'  => new DateTime(),
        ];

        if ($type) {
            Token::validateType($type);

            $qb->andWhere($qb->expr()->eq('t.type', ':type'));

            $parameters['type'] = $type;
        }

        if ($user) {
            $qb->andWhere($qb->expr()->isNotNull('t.user'));
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return $qb
            ->setMaxResults(1)
            ->getQuery()
            ->useQueryCache(true)
            ->setParameters($parameters)
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
            ->delete(Token::class, 't')
            ->andWhere($qb->expr()->isNotNull('t.expiresAt'))
            ->andWhere($qb->expr()->lt('t.expiresAt', ':now'))
            ->getQuery()
            ->useQueryCache(true)
            ->setParameter('now', new DateTime(), Types::DATETIME_MUTABLE);
    }
}
