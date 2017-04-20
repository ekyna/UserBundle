<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Menu;

use Ekyna\Bundle\UserBundle\Event\MenuEvent;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MenuBuilder
 * @package Ekyna\Bundle\UserBundle\Service\Menu
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class MenuBuilder
{
    private EventDispatcherInterface      $dispatcher;
    private FactoryInterface              $factory;
    private AuthorizationCheckerInterface $authorization;
    private TokenStorageInterface         $tokenStorage;
    private array                         $config;

    public function __construct(
        EventDispatcherInterface      $dispatcher,
        FactoryInterface              $factory,
        AuthorizationCheckerInterface $authorization,
        TokenStorageInterface         $tokenStorage,
        array                         $config
    ) {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->authorization = $authorization;
        $this->tokenStorage = $tokenStorage;
        $this->config = $config;
    }

    public function createAccountMenu(): ItemInterface
    {
        // TODO user context / caching

        $menu = $this->factory->createItem('root');
        $user = $this->getUser();

        if (null === $user) {
            return $menu;
        }

        // Change password
        $menu
            ->addChild('dashboard', [
                'route' => 'ekyna_user_account_index',
            ])
            ->setLabel('account.menu.dashboard')
            ->setExtra('translation_domain', 'EkynaUser');

        // Profile
        if ($this->config['profile']['enabled']) {
            $menu
                ->addChild('profile', [
                    'route' => 'ekyna_user_account_profile',
                ])
                ->setLabel('account.menu.profile')
                ->setExtra('translation_domain', 'EkynaUser');
        }

        // Configure menu event
        $this->dispatcher->dispatch(
            new MenuEvent($this->factory, $menu, $user),
            MenuEvent::CONFIGURE_ACCOUNT
        );

        // Change password
        $menu
            ->addChild('change_password', [
                'route' => 'ekyna_user_account_change_password',
            ])
            ->setLabel('account.menu.password')
            ->setExtra('translation_domain', 'EkynaUser');

        // Logout
        $menu
            ->addChild('logout', [
                'route' => 'ekyna_user_security_logout',
            ])
            ->setLabel('account.menu.logout')
            ->setExtra('translation_domain', 'EkynaUser');

        return $menu;
    }

    public function createWidgetMenu(): ItemInterface
    {
        // TODO user context / caching

        $menu = $this->factory->createItem('root');
        $user = $this->getUser();

        if (null === $user) {
            $menu
                ->addChild('login', [
                    'route' => 'ekyna_user_security_login',
                ])
                ->setLabel('account.menu.login')
                ->setExtra('translation_domain', 'EkynaUser');

            return $menu;
        }

        if (!(
            $this->authorization->isGranted('IS_AUTHENTICATED_FULLY')
            || $this->authorization->isGranted('IS_AUTHENTICATED_REMEMBERED')
        )) {
            return $menu;
        }

        // User email
        $menu->addChild($user->getEmail(), ['uri' => null]);

        // Account
        $menu
            ->addChild('account', [
                'route' => 'ekyna_user_account_index',
            ])
            ->setLabel('account.title')
            ->setExtra('translation_domain', 'EkynaUser');

        // Configure menu event
        $this->dispatcher->dispatch(
            new MenuEvent($this->factory, $menu, $user),
            MenuEvent::CONFIGURE_WIDGET
        );

        // Exit impersonate
        /* TODO if ($this->authorization->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $menu->addChild('ekyna_user.account.menu.exit_impersonate', ['uri' => '/?_switch_user=_exit']);
        }*/

        // Logout
        $menu
            ->addChild('logout', [
                'route' => 'ekyna_user_security_logout',
            ])
            ->setLabel('account.menu.logout')
            ->setExtra('translation_domain', 'EkynaUser');

        return $menu;
    }

    protected function getUser(): ?UserInterface
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $token->getUser();
        }

        return null;
    }
}
