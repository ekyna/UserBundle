<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Ekyna\Bundle\UserBundle\Entity\OAuthToken;
use Ekyna\Component\User\Repository\AbstractOAuthTokenRepository;

/**
 * Class OAuthTokenRepository
 * @package Ekyna\Bundle\UserBundle\Repository
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class OAuthTokenRepository extends AbstractOAuthTokenRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuthToken::class);
    }
}
