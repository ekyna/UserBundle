<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;


/**
 * Class SecurityListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @todo Move in AdminBundle
 */
class SecurityListener implements EventSubscriberInterface
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

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
     * @param AuthorizationCheckerInterface  $authorizationChecker
     * @param Mailer                         $mailer
     * @param array                          $config
     */
    public function __construct(
        AccessDecisionManagerInterface $accessDecisionManager,
        AuthorizationCheckerInterface $authorizationChecker,
        Mailer $mailer,
        array $config
    ) {
        $this->accessDecisionManager = $accessDecisionManager;
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * Interactive login event handler.
     *
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->notifyUser($event->getAuthenticationToken());
    }

    /**
     * Notifies the user about successful interactive login.
     *
     * @param TokenInterface $token
     */
    protected function notifyUser(TokenInterface $token)
    {
        if (!$this->config['admin_login']) {
            return;
        }

        // Skip non fully authenticated
        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return;
        }

        // Only for Admins and fully authenticated
        if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return;
        }

        /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
        $user = $token->getUser();

        $this->mailer->sendSuccessfulLoginEmailMessage($user);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => ['onInteractiveLogin'],
        ];
    }
}
