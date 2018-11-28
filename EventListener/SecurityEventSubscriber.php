<?php

namespace Ekyna\Bundle\UserBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Helper\FlashHelper;
use Ekyna\Bundle\UserBundle\Service\Provider\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
     * @var FlashHelper
     */
    private $flashHelper;


    /**
     * Constructor.
     *
     * @param UserProviderInterface         $userProvider
     * @param FlashHelper                   $flashHelper
     */
    public function __construct(UserProviderInterface $userProvider, FlashHelper $flashHelper)
    {
        $this->userProvider = $userProvider;
        $this->flashHelper = $flashHelper;
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
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => ['onInteractiveLogin', 1024],
        ];
    }
}
