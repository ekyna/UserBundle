<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;


/**
 * Class AuthenticationListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $config;


    /**
     * Constructor.
     *
     * @param AccessDecisionManagerInterface $accessDecisionManager
     * @param Mailer $mailer
     */
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager, Mailer $mailer, array $config)
    {
        $this->accessDecisionManager = $accessDecisionManager;
        $this->mailer = $mailer;
        $this->config = $config;
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
        if (!$this->config['admin_login']) {
            return;
        }

        $token = $event->getAuthenticationToken();
        $userIsAdmin = $this->accessDecisionManager->decide($token, array('ROLE_ADMIN'));

        // Only for Admins and fully authenticated
        if (!($userIsAdmin && $token instanceof UsernamePasswordToken)) {
            return;
        }

        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $token->getUser();

        $this->mailer->sendSuccessfulLoginEmailMessage($user);
    }
}
