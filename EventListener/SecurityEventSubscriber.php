<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Helper\FlashHelper;
use Ekyna\Bundle\UserBundle\Event\AuthenticationEvent;
use Ekyna\Bundle\UserBundle\Service\Account\WidgetRenderer;
use Ekyna\Bundle\UserBundle\Service\Provider\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class SecurityEventSubscriber
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SecurityEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var WidgetRenderer
     */
    private $widgetRenderer;

    /**
     * @var FlashHelper
     */
    private $flashHelper;


    /**
     * Constructor.
     *
     * @param UserProviderInterface $userProvider
     * @param WidgetRenderer        $widgetRenderer
     * @param FlashHelper           $flashHelper
     */
    public function __construct(
        UserProviderInterface $userProvider,
        WidgetRenderer $widgetRenderer,
        FlashHelper $flashHelper
    ) {
        $this->userProvider = $userProvider;
        $this->widgetRenderer = $widgetRenderer;
        $this->flashHelper = $flashHelper;
    }

    /**
     * Authentication success handler.
     *
     * @param AuthenticationEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $token = $event->getToken();
        if ($token instanceof OAuthToken) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return;
        }

        /** @var \Ekyna\Bundle\UserBUndle\Model\UserInterface $user */
        $user = $token->getUser();

        $redirect = $this->getResponseRedirection($event->getResponse());

        // JSON response
        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $event->setResponse(JsonResponse::create([
                'success'  => true,
                'username' => (string)$user,
                'redirect' => $redirect,
            ]));
        }

        // Widget XHR response
        //if (in_array('application/xml', $request->getAcceptableContentTypes())) {
        $response = new Response($this->widgetRenderer->render($user, $redirect));
        $response->headers->set('Content-Type', 'application/xml');
        $event->setResponse($response);
        //}
    }

    /**
     * Authentication failure handler.
     *
     * @param AuthenticationEvent $event
     */
    public function onAuthenticationFailure(AuthenticationEvent $event)
    {
        $token = $event->getToken();
        if ($token instanceof OAuthToken) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return;
        }

        if (!in_array('application/json', $request->getAcceptableContentTypes())) {
            return;
        }

        $event->setResponse(JsonResponse::create([
            'success'  => false,
            'message'  => $event->getException()->getMessageKey(),
            'redirect' => $this->getResponseRedirection($event->getResponse()),
        ], 401));
    }

    /**
     * Interactive login event handler.
     *
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->userProvider->reset();

        // Flash about OAuth login
        $token = $event->getAuthenticationToken();
        if ($token instanceof OAuthToken) {
            $this->flashHelper->addTrans('info', 'ekyna_user.account.login.oauth', [
                '%owner%' => ucfirst($token->getResourceOwnerName()),
            ]);
        }
    }

    /**
     * Returns the redirection path/url if any from the response.
     *
     * @param Response $response
     *
     * @return string|null
     */
    private function getResponseRedirection(Response $response = null)
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

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvent::SUCCESS      => ['onAuthenticationSuccess', -1024],
            AuthenticationEvent::FAILURE      => ['onAuthenticationFailure', -1024],
            SecurityEvents::INTERACTIVE_LOGIN => ['onInteractiveLogin', 1024],
        ];
    }
}
