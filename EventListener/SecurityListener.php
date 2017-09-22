<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Helper\FlashHelper;
use Ekyna\Bundle\UserBundle\Mailer\Mailer;
use Ekyna\Bundle\UserBundle\Service\Provider\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class SecurityListener
 * @package Ekyna\Bundle\UserBundle\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SecurityListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var FlashHelper
     */
    private $flashHelper;

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
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param UserProviderInterface         $userProvider
     * @param FlashHelper                   $flashHelper
     * @param Mailer                        $mailer
     * @param array                         $config
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        UserProviderInterface $userProvider,
        FlashHelper $flashHelper,
        Mailer $mailer,
        array $config
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->userProvider = $userProvider;
        $this->flashHelper = $flashHelper;
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * Interactive login event handler.
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

        $this->notifyUser();
    }

    /**
     * Notifies the user about successful interactive login.
     */
    protected function notifyUser()
    {
        if (!$this->config['admin_login']) {
            return;
        }

        if (null === $user = $this->userProvider->getUser()) {
            return;
        }

        // Skip non fully authenticated
        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return;
        }

        // Only for Admins and fully authenticated
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return;
        }

        $this->mailer->sendSuccessfulLoginEmailMessage($user);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => ['onInteractiveLogin', 1024],
        ];
    }
}
