<?php

namespace Ekyna\Bundle\UserBundle\Service\Provider;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
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
     * @var UserInterface
     */
    private $user;

    /**
     * @var bool
     */
    private $initialized = false;


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
    public function hasUser()
    {
        $this->initialize();

        return null !== $this->user;
    }

    /**
     * @inheritdoc
     */
    public function getUser()
    {
        $this->initialize();

        return $this->user;
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        $this->user = null;
        $this->initialized = false;
    }

    public function clear()
    {
        $this->user = null;
        $this->initialized = true;
    }

    /**
     * Loads the user once.
     */
    private function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        $user = $token->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            return;
        }

        $this->user = $user;
    }
}
