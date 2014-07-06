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
        if ($targetUrl = $request->getSession()->get('_ekyna.login_success.target_path')) {
            $request->getSession()->remove('_ekyna.login_success.target_path');
        
            return $this->httpUtils->createRedirectResponse($request, $targetUrl);
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}
