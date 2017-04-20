<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\UserBUndle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Service\Account\WidgetRenderer;
use Ekyna\Component\User\EventListener\SecurityEventListener as BaseListener;
use Ekyna\Component\User\Service\UserProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

use function in_array;

/**
 * Class SecurityEventListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SecurityEventListener extends BaseListener
{
    private WidgetRenderer $widgetRenderer;

    public function __construct(
        UserProviderInterface $userUserProvider,
        WidgetRenderer        $widgetRenderer
    ) {
        parent::__construct($userUserProvider);

        $this->widgetRenderer = $widgetRenderer;
    }

    public function onAuthenticationSuccess(LoginSuccessEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return;
        }

        /** @var UserInterface $user */
        $user = $this->userProvider->getUser();

        $redirect = $this->getResponseRedirection($event->getResponse());

        // JSON response
        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $event->setResponse(new JsonResponse([
                'success'  => true,
                'username' => (string)$user,
                'redirect' => $redirect,
            ]));

            return;
        }

        // Widget XHR response
        //if (in_array('application/xml', $request->getAcceptableContentTypes())) {
        $response = new Response($this->widgetRenderer->render($user, $redirect));
        $response->headers->set('Content-Type', 'application/xml');
        $event->setResponse($response);
        //}
    }

    public function onAuthenticationFailure(LoginFailureEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return;
        }

        if (!in_array('application/json', $request->getAcceptableContentTypes())) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'success'  => false,
            'message'  => $event->getException()->getMessageKey(),
            'redirect' => $this->getResponseRedirection($event->getResponse()),
        ], 401));
    }

    private function getResponseRedirection(Response $response = null): ?string
    {
        if (!$response) {
            return null;
        }

        if ($response instanceof RedirectResponse) {
            return $response->getTargetUrl();
        }

        if (!in_array($response->getStatusCode(), [301, 302])) {
            return null;
        }

        if (!$response->headers->has('Location')) {
            return null;
        }

        if (empty($redirect = $response->headers->get('Location'))) {
            return null;
        }

        return $redirect;
    }
}
