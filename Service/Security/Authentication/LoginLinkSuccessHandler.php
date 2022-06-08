<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Security\Authentication;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

use function urldecode;

/**
 * Class LoginLinkSuccessHandler
 * @package Ekyna\Bundle\UserBundle\Service\Security\Authentication
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class LoginLinkSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        if ($request->query->has('redirect_after')) {
            $uri = urldecode($request->query->get('redirect_after'));
        } else {
            $uri = $this->urlGenerator->generate('ekyna_user_account_index');
        }

        return new RedirectResponse($uri);
    }
}
