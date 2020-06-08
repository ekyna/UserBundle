<?php

namespace Ekyna\Bundle\UserBundle\Service\Security;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\UserBundle\Entity\LoginToken;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Repository\LoginTokenRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class LoginTokenManager
 * @package Ekyna\Bundle\UserBundle\Service\Security
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class LoginTokenManager
{
    public const TOKEN_PARAMETER = 'token';
    public const AFTER_PARAMETER = 'after';
    public const TOKEN_EXPIRES = '+5 days';

    /**
     * @var LoginTokenRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $firewall;


    /**
     * Constructor.
     *
     * @param LoginTokenRepository     $repository
     * @param EntityManagerInterface   $manager
     * @param TokenStorageInterface    $tokenStorage
     * @param EventDispatcherInterface $dispatcher
     * @param UrlGeneratorInterface    $urlGenerator
     * @param string                   $firewall
     */
    public function __construct(
        LoginTokenRepository $repository,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $dispatcher,
        UrlGeneratorInterface $urlGenerator,
        string $firewall = 'front'
    ) {
        $this->repository   = $repository;
        $this->manager      = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->dispatcher   = $dispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->firewall     = $firewall;
    }

    /**
     * Creates a new login token.
     *
     * @param UserInterface $user
     *
     * @return LoginToken
     */
    public function createToken(UserInterface $user): LoginToken
    {
        if (null !== $token = $this->repository->findOneByUser($user)) {
            return $token;
        }

        $token = new LoginToken();
        $token
            ->setUser($user)
            ->setToken(bin2hex(random_bytes(16)))
            ->setExpiresAt(new DateTime(self::TOKEN_EXPIRES));

        $this->manager->persist($token);

        return $token;
    }

    /**
     * Generates the token login url.
     *
     * @param UserInterface $user
     * @param string|null   $after
     *
     * @return string
     */
    public function generateLoginUrl(UserInterface $user, string $after = null): string
    {
        $token = $this->createToken($user);

        $parameters = [
            self::TOKEN_PARAMETER => $token->getToken(),
        ];

        if (!empty($after)) {
            $parameters[self::AFTER_PARAMETER] = $after;
        }

        return $this
            ->urlGenerator
            ->generate('ekyna_user_security_token_login', $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * Login using the token.
     *
     * @param Request $request
     *
     * @return string The url to redirect after.
     */
    public function login(Request $request): string
    {
        if (empty($hash = $request->attributes->get(LoginTokenManager::TOKEN_PARAMETER))) {
            throw new TokenNotFoundException();
        }

        if (null === $token = $this->repository->findOneByHash($hash)) {
            throw new TokenNotFoundException();
        }

        $user = $token->getUser();

        $token = new UsernamePasswordToken($user, $user->getPassword(), $this->firewall, $user->getRoles());
        $this->tokenStorage->setToken($token);

        $event = new InteractiveLoginEvent($request, $token);
        $this->dispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);

        if (!empty($url = $request->query->get(self::AFTER_PARAMETER))) {
            return $url;
        }

        return $this->urlGenerator->generate('ekyna_user_account_index');
    }
}
