<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\Service\Menu\MenuBuilder;
use Knp\Menu\MenuItem;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // Menu builder
        ->set('ekyna_user.menu_builder', MenuBuilder::class)
            ->args([
                service('event_dispatcher'),
                service('knp_menu.factory'),
                service('security.authorization_checker'),
                service('security.token_storage'),
                abstract_arg('Account configuration'),
            ])

        // Side menu
        ->set('ekyna_user.menu.account', MenuItem::class)
            ->factory([
                service('ekyna_user.menu_builder'),
                'createAccountMenu'
            ])
            ->args([
                service('request_stack'),
            ])
            ->tag('knp_menu.menu', ['alias' => 'ekyna_user.account'])

        // Widget menu
        ->set('ekyna_user.menu.widget', MenuItem::class)
            ->factory([
                service('ekyna_user.menu_builder'),
                'createWidgetMenu'
            ])
            ->args([
                service('request_stack'),
            ])
            ->tag('knp_menu.menu', ['alias' => 'ekyna_user.widget'])
    ;
};
