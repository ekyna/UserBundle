<?php

namespace Ekyna\Bundle\UserBundle\Service\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;

/**
 * Class AuthenticationFailureHandler
 * @package Ekyna\Bundle\UserBundle\Service\Security
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
    /**
     * @inheritdoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest() && in_array('application/json', $request->getAcceptableContentTypes())) {
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessageKey(),
            ], 401);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }
}
