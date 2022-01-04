<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\UserBundle\Entity\Token;
use Ekyna\Bundle\UserBundle\Model\TokenTrait;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Repository\TokenRepository;
use Ekyna\Component\User\Service\Security\SecurityUtil;

use function bin2hex;
use function random_bytes;

/**
 * Class LoginTokenManager
 * @package Ekyna\Bundle\UserBundle\Service\Security
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class TokenManager
{
    use TokenTrait;

    private TokenRepository        $repository;
    private SecurityUtil           $securityUtil;
    private EntityManagerInterface $manager;

    public function __construct(
        TokenRepository        $repository,
        SecurityUtil           $securityUtil,
        EntityManagerInterface $manager
    ) {
        $this->repository = $repository;
        $this->securityUtil = $securityUtil;
        $this->manager = $manager;
    }

    public function createToken(string $type, ?UserInterface $user, array $data = []): Token
    {
        Token::validateType($type);

        // TODO Use security util
        // 64 length random token
        /** @noinspection PhpUnhandledExceptionInspection */
        $hash = bin2hex(random_bytes(32));

        $token = new Token($type);
        $token
            ->setUser($user)
            ->setHash($hash)
            ->setData($data);

        $this->update($token);

        return $token;
    }

    public function update(Token $token): void
    {
        $token->setExpiresAt($this->getExpiresAt($token->getType()));

        $this->manager->persist($token);
        $this->manager->flush();
    }

    public function remove(Token $token): void
    {
        $this->manager->remove($token);
        $this->manager->flush();
    }

    public function findToken(string $hash, string $type, bool $user = true): ?Token
    {
        return $this->repository->findOneByHash($hash, $type, $user);
    }
}
