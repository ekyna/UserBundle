<?php

namespace Ekyna\Bundle\UserBundle\Service\Security;

use Ekyna\Bundle\UserBundle\Service\Account\WidgetRenderer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

/**
 * Class AuthenticationSuccessHandler
 * @package Ekyna\Bundle\UserBundle\Service\Security
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @var WidgetRenderer
     */
    private $widgetRenderer;

    /**
     * Sets the widgetRenderer.
     *
     * @param WidgetRenderer $widgetRenderer
     */
    public function setWidgetRenderer(WidgetRenderer $widgetRenderer)
    {
        $this->widgetRenderer = $widgetRenderer;
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            if (in_array('application/json', $request->getAcceptableContentTypes())) {
                return new JsonResponse([
                    'success'  => true,
                    'username' => $token->getUsername(),
                ]);
            }

            // Widget XHR response
            $response = new Response($this->widgetRenderer->render($token->getUser()));
            $response->headers->set('Content-Type', 'application/xml');
            return $response;
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}
