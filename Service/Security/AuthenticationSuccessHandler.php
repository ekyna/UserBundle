<?php

namespace Ekyna\Bundle\UserBundle\Service\Security;

use Ekyna\Bundle\UserBundle\Event\AuthenticationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

/**
 * Class AuthenticationSuccessHandler
 * @package Ekyna\Bundle\UserBundle\Service\Security
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    /**
     * Sets the event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $event = new AuthenticationEvent($request, $token);

        $this->eventDispatcher->dispatch(AuthenticationEvent::SUCCESS, $event);

        if ($response = $event->getResponse()) {
            return $response;
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}
