<?php

namespace Ekyna\Bundle\UserBundle\Service\Security;

use Ekyna\Bundle\UserBundle\Event\AuthenticationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    /**
     * Sets the eventDispatcher.
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
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $event = new AuthenticationEvent($request, null, $exception);

        $this->eventDispatcher->dispatch(AuthenticationEvent::FAILURE, $event);

        if ($response = $event->getResponse()) {
            return $response;
        }

        return parent::onAuthenticationFailure($request, $exception);
    }
}
