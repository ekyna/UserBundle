<?php

namespace Ekyna\Bundle\UserBundle\Service\Security;

use Ekyna\Bundle\UserBundle\Service\Provider\UserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * Class LogoutHandler
 * @package Ekyna\Bundle\UserBundle\Service\Security
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class LogoutHandler implements LogoutHandlerInterface
{
    /**
     * @var UserProvider
     */
    private $userProvider;


    /**
     * Constructor.
     *
     * @param UserProvider $userProvider
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @inheritDoc
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $this->userProvider->clear();
    }
}
