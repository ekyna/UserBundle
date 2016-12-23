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
        $menu = $this->factory->createItem('root');

        $this->dispatcher->dispatch(MenuEvent::CONFIGURE, new MenuEvent($this->factory, $menu));

        // Change password
        $menu->addChild('ekyna_user.account.menu.password', [
            'route' => 'fos_user_change_password',
        ]);

        // Logout
        $menu->addChild('ekyna_user.account.menu.logout', [
            'route' => 'fos_user_security_logout',
        ]);

        return $menu;
    }

    /**
     * Creates the user menu.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createUserMenu()
    {
        $menu = $this->factory->createItem('root');

        if ($this->accountEnabled) {
            if (null !== $token = $this->tokenStorage->getToken()) {
                if ($this->authorization->isGranted('IS_AUTHENTICATED_FULLY') || $this->authorization->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
                    $user = $token->getUser();
                    $item = $menu->addChild($user->getEmail(), ['uri' => '#']);
                    $item->addChild('ekyna_user.account.menu.my_profile', ['route' => 'fos_user_profile_show']);
                    if ($this->authorization->isGranted('ROLE_ADMIN')) {
                        $item->addChild('ekyna_user.account.menu.backend', ['route' => 'ekyna_admin']);
                    }
                    $item->addChild('ekyna_user.account.menu.logout', ['route' => 'fos_user_security_logout']);

                    return $menu;
                }
            }
            $menu->addChild('ekyna_user.account.menu.login', ['route' => 'fos_user_security_login']);
        }

        return $menu;
    }
}
