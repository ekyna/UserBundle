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
        // TODO: send login warning email if has ROLE_ADMIN

        return parent::onAuthenticationSuccess($request, $token);
    }

    /**
     * {@inheritdoc}
     */
    protected function determineTargetUrl(Request $request)
    {
        if (null !== $targetUrl = $request->getSession()->get('_ekyna.login_success.target_path')) {
            $request->getSession()->remove('_ekyna.login_success.target_path');

            return $targetUrl;
        }

        return parent::determineTargetUrl($request);
    }
}
