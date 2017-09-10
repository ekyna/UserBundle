<?php

namespace Ekyna\Bundle\UserBundle\Service\Provider;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserProvider
 * @package Ekyna\Bundle\UserBundle\Service\Provider
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;


    /**
     * Constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritdoc
     */
    public function getUser()
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            if (is_object($user = $token->getUser())) {
                return $user;
            }
        }

        return null;
    }
}
