<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Security\Authentication;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class LoginLinkFailureHandler
 * @package Ekyna\Bundle\UserBundle\Service\Security\LoginLink
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class LoginLinkFailureHandler implements AuthenticationFailureHandlerInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $uri = $this->urlGenerator->generate('ekyna_user_security_login');

        return new RedirectResponse($uri);
    }
}
