<?php

namespace Ekyna\Bundle\UserBundle\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

/**
 * Class AuthenticationSuccessHandler
 * @package Ekyna\Bundle\UserBundle\Authentication
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $session = $request->getSession();
        if ($session->has('_ekyna.login_success.target_path')) {
            $targetUrl = $session->get('_ekyna.login_success.target_path');
            $session->remove('_ekyna.login_success.target_path');
        
            return $this->httpUtils->createRedirectResponse($request, $targetUrl);
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}
