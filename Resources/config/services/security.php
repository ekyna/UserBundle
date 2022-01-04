<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\EventListener\SecurityEventListener;
use Ekyna\Bundle\UserBundle\Manager\TokenManager;
use Ekyna\Bundle\UserBundle\Repository\OAuthTokenRepository;
use Ekyna\Bundle\UserBundle\Repository\TokenRepository;
use Ekyna\Component\User\Service\OAuth\OAuthPassportGenerator;
use Ekyna\Component\User\Service\OAuth\RoutingLoader;
use Ekyna\Component\User\Service\Security\LoginFormAuthenticator;
use Ekyna\Component\User\Service\Security\UserProvider;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // Token repository
        ->set('ekyna_user.repository.token', TokenRepository::class)
            ->args([
                service('doctrine'),
            ])
            ->tag('doctrine.repository_service')

        // Token manager
        ->set('ekyna_user.manager.token', TokenManager::class)
            ->args([
                service('ekyna_user.repository.token'),
                service('ekyna_admin.security_util'),
                service('doctrine.orm.entity_manager'),
            ])

        // User provider
        ->set('ekyna_user.security.user_provider', UserProvider::class)
            ->args([
                service('ekyna_user.repository.user'),
                service('ekyna_user.manager.user'),
            ])

        // Form login authenticator
        ->set('ekyna_user.security.authenticator.form_login', LoginFormAuthenticator::class)
            ->args([
                service('ekyna_user.security.user_provider'),
                service('router'),
                'ekyna_user_security_login',
                'ekyna_user_account_index',
            ])

        // OAuth passport generator
        ->set('ekyna_user.security.oauth_passport_generator', OAuthPassportGenerator::class)
            ->args([
                service('ekyna_user.repository.user'),
                service('ekyna_user.manager.user'),
                service('ekyna_user.factory.user'),
                service('ekyna_user.repository.oauth_token'),
                service('doctrine.orm.default_entity_manager'),
            ])

        // OAuth Token repository
        ->set('ekyna_user.repository.oauth_token', OAuthTokenRepository::class)
            ->args([
                service('doctrine'),
            ])
            ->tag('doctrine.repository_service')

        // OAuth routing loader
        ->set('ekyna_user.routing.oauth_loader', RoutingLoader::class)
            ->args([
                service('knpu.oauth2.registry'),
                'ekyna_user',
                '/'
            ])
            ->tag('routing.loader')

        // Security listener
        ->set('ekyna_user.listener.security', SecurityEventListener::class)
            ->args([
                service('ekyna_user.provider.user'),
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
            ])
    ;
};
