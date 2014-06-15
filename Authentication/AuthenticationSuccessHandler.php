<?php

namespace Ekyna\Bundle\UserBundle\Authentication;

use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * AuthenticationSuccessHandler.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return parent::onAuthenticationSuccess($request, $token);
    }
}
