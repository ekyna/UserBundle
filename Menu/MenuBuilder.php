<?php

namespace Ekyna\Bundle\UserBundle\Menu;

use Ekyna\Bundle\UserBundle\Event\MenuEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MenuBuilder
 * @package Ekyna\Bundle\UserBundle\Menu
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class MenuBuilder
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorization;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var bool
     */
    protected $accountEnabled;


    /**
     * Constructor.
     *
     * @param EventDispatcherInterface      $dispatcher
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authorization
     * @param TokenStorageInterface         $tokenStorage
     * @param array                         $config
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        FactoryInterface $factory,
        AuthorizationCheckerInterface $authorization,
        TokenStorageInterface $tokenStorage,
        array $config
    ) {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->authorization = $authorization;
        $this->tokenStorage = $tokenStorage;

        $this->accountEnabled = $config['account']['enable'];
    }

    /**
     * Creates the account menu.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createAccountMenu()
    {
        // TODO user context / caching

        $menu = $this->factory->createItem('root');
        $user = $this->getUser();

        if (null !== $user) {
            // Change password
            $menu->addChild('ekyna_user.account.menu.dashboard', [
                'route' => 'ekyna_user_account_index',
            ]);

            // Configure menu event
            $this->dispatcher->dispatch(
                MenuEvent::CONFIGURE_ACCOUNT,
                new MenuEvent($this->factory, $menu, $user)
            );

            // Change password
            $menu->addChild('ekyna_user.account.menu.password', [
                'route' => 'fos_user_change_password',
            ]);

            // Logout
            $menu->addChild('ekyna_user.account.menu.logout', [
                'route' => 'fos_user_security_logout',
            ]);
        }

        return $menu;
    }

    /**
     * Creates the user menu.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createWidgetMenu()
    {
        // TODO user context / caching

        $menu = $this->factory->createItem('root');
        $user = $this->getUser();

        if (null !== $user) {
            if ($this->authorization->isGranted('IS_AUTHENTICATED_FULLY') || $this->authorization->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                // User email
                $menu->addChild($user->getEmail(), ['uri' => null]);

                // Account
                $menu->addChild('ekyna_user.account.title', [
                    'route' => 'ekyna_user_account_index'
                ]);

                // Configure menu event
                $this->dispatcher->dispatch(
                    MenuEvent::CONFIGURE_WIDGET,
                    new MenuEvent($this->factory, $menu, $user)
                );

                // Administration
                if ($this->authorization->isGranted('ROLE_ADMIN')) {
                    $menu->addChild('ekyna_user.account.menu.backend', ['route' => 'ekyna_admin']);
                }

                // Exit impersonate
                if ($this->authorization->isGranted('ROLE_PREVIOUS_ADMIN')) {
                    $menu->addChild('ekyna_user.account.menu.exit_impersonate', ['uri' => '/?_switch_user=_exit']);
                }

                // Logout
                $menu->addChild('ekyna_user.account.menu.logout', [
                    'route' => 'fos_user_security_logout'
                ]);

                return $menu;
            }
        } else {
            $menu->addChild('ekyna_user.account.menu.login', [
                'route' => 'fos_user_security_login'
            ]);
        }

        return $menu;
    }

    /**
     * Returns the user.
     *
     * @return \Ekyna\Bundle\UserBundle\Model\UserInterface|null
     */
    protected function getUser()
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            return $token->getUser();
        }

        return null;
    }
}
