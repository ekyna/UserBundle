<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

/**
 * Class AuthenticationListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * Constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            //AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        );
    }

    /**
     * Handle login failure event.
     *
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        // TODO warn about login attempt on admin users accounts.
    }

    /**
     * Handle login success event.
     *
     * @param AuthenticationEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $token = $event->getAuthenticationToken();

        /** @var \Ekyna\Bundle\UserBundle\Entity\User $user */
        $user = $token->getUser();

        // Only for Admins and fully authenticated
        if (!($user->getGroup()->hasRole('ROLE_ADMIN') && $token instanceof UsernamePasswordToken)) {
            return;
        }

        $this->mailer->sendSuccessfulLoginEmailMessage($user);
    }
}
