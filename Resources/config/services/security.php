<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\EventListener\SecurityEventListener;
use Ekyna\Bundle\UserBundle\Manager\TokenManager;
use Ekyna\Bundle\UserBundle\Repository\OAuthTokenRepository;
use Ekyna\Bundle\UserBundle\Repository\TokenRepository;
use Ekyna\Bundle\UserBundle\Service\Security\Authentication\LoginLinkFailureHandler;
use Ekyna\Bundle\UserBundle\Service\Security\Authentication\LoginLinkSuccessHandler;
use Ekyna\Bundle\UserBundle\Service\Security\LoginLinkHelper;
use Ekyna\Component\User\Service\OAuth\OAuthPassportGenerator;
use Ekyna\Component\User\Service\OAuth\RoutingLoader;
use Ekyna\Component\User\Service\Security\LoginFormAuthenticator;
use Ekyna\Component\User\Service\Security\UserProvider;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    // Token repository
    $services
        ->set('ekyna_user.repository.token', TokenRepository::class)
        ->args([
            service('doctrine'),
        ])
        ->tag('doctrine.repository_service');

    // Token manager
    $services
        ->set('ekyna_user.manager.token', TokenManager::class)
        ->args([
            service('ekyna_user.repository.token'),
            service('ekyna_admin.security_util'),
            service('doctrine.orm.entity_manager'),
        ]);

    // User provider
    $services
        ->set('ekyna_user.security.user_provider', UserProvider::class)
        ->args([
            service('ekyna_user.repository.user'),
            service('ekyna_user.manager.user'),
        ]);

    // Login link helper
    $services
        ->set('ekyna_user.security.login_link_helper', LoginLinkHelper::class)
        ->args([
            service('security.authenticator.login_link_handler.main')->nullOnInvalid(),
        ])
        ->tag('twig.runtime');

    // Login link success handler
    $services
        ->set('ekyna_user.security.authentication.login_link_success', LoginLinkSuccessHandler::class)
        ->args([
            service('router'),
        ]);

    // Login link failure handler
    $services
        ->set('ekyna_user.security.authentication.login_link_failure', LoginLinkFailureHandler::class)
        ->args([
            service('router'),
        ]);

    // Form login authenticator
    $services
        ->set('ekyna_user.security.authenticator.form_login', LoginFormAuthenticator::class)
        ->args([
            service('ekyna_user.security.user_provider'),
            service('router'),
            'ekyna_user_security_login',
            'ekyna_user_account_index',
        ]);

    // OAuth passport generator
    $services
        ->set('ekyna_user.security.oauth_passport_generator', OAuthPassportGenerator::class)
        ->args([
            service('ekyna_user.repository.user'),
            service('ekyna_user.manager.user'),
            service('ekyna_user.factory.user'),
            service('ekyna_user.repository.oauth_token'),
            service('doctrine.orm.default_entity_manager'),
        ]);

    // OAuth Token repository
    $services
        ->set('ekyna_user.repository.oauth_token', OAuthTokenRepository::class)
        ->args([
            service('doctrine'),
        ])
        ->tag('doctrine.repository_service');

    // OAuth routing loader
    $services
        ->set('ekyna_user.routing.oauth_loader', RoutingLoader::class)
        ->args([
            service('knpu.oauth2.registry'),
            'ekyna_user',
            '/',
        ])
        ->tag('routing.loader');

    // Security listener
    $services
        ->set('ekyna_user.listener.security', SecurityEventListener::class)
        ->args([
            service('ekyna_user.provider.user'),
            service('ekyna_user.manager.user'),
            service('ekyna_user.account.widget_renderer'),
        ])
        ->tag('kernel.event_listener', [
            'dispatcher' => 'security.event_dispatcher.main',
            'event'      => LoginSuccessEvent::class,
            'method'     => 'onAuthenticationSuccess',
            'priority'   => -1024,
        ])
        ->tag('kernel.event_listener', [
            'dispatcher' => 'security.event_dispatcher.main',
            'event'      => LoginFailureEvent::class,
            'method'     => 'onAuthenticationFailure',
            'priority'   => -1024,
        ])
        ->tag('kernel.event_listener', [
            'dispatcher' => 'security.event_dispatcher.main',
            'event'      => LoginSuccessEvent::class,
            'method'     => 'onLoginSuccess',
            'priority'   => 1024,
        ])
        ->tag('kernel.event_listener', [
            'dispatcher' => 'security.event_dispatcher.main',
            'event'      => LogoutEvent::class,
            'method'     => 'onLogout',
            'priority'   => 1024,
        ]);
};
