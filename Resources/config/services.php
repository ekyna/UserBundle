<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\Action\User\ClearPasswordRequestAction;
use Ekyna\Bundle\UserBundle\Action\User\GeneratePasswordAction;
use Ekyna\Bundle\UserBundle\Action\User\UseSessionAction;
use Ekyna\Bundle\UserBundle\EventListener\UserEventSubscriber;
use Ekyna\Bundle\UserBundle\Service\Account\WidgetRenderer;
use Ekyna\Bundle\UserBundle\Service\Mailer\UserMailer;
use Ekyna\Bundle\UserBundle\Service\Routing\AccountLoader;
use Ekyna\Bundle\UserBundle\Twig\UserExtension;
use Ekyna\Component\User\Service\UserProvider;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // User provider
        ->set('ekyna_user.provider.user', UserProvider::class)
            ->args([
                service('security.token_storage'),
                param('ekyna_user.class.user'),
            ])

        // User generate password action
        ->set('ekyna_user.action.user.generate_password', GeneratePasswordAction::class)
            ->args([
                service('ekyna_admin.security_util'),
            ])
            ->tag('ekyna_resource.action')

        // User clear password request action
        ->set('ekyna_user.action.user.clear_password_request', ClearPasswordRequestAction::class)
            ->args([
                3600, // TODO Make configurable
            ])
            ->tag('ekyna_resource.action')

        // User use session action
        ->set('ekyna_user.action.user.use_session', UseSessionAction::class)
            ->tag('ekyna_resource.action')

        // User event listener
        ->set('ekyna_user.listener.user', UserEventSubscriber::class)
            ->args([
                service('ekyna_user.mailer'),
            ])
            ->call('setPasswordHasher', [service('security.password_encoder')]) // TODO (Sf 6) security.user_password_hasher
            ->call('setSecurityUtil', [service('ekyna_admin.security_util')])
            ->tag('resource.event_subscriber')

        // Routing (account) load
        ->set('ekyna_user.routing.account_loader', AccountLoader::class)
            ->args([
                abstract_arg('Configuration'),
                param('kernel.environment'),
            ])
            ->tag('routing.loader')

        // Widget renderer
        ->set('ekyna_user.account.widget_renderer', WidgetRenderer::class)
            ->args([
                service('twig'),
            ])

        // Twig extension
        ->set('ekyna_user.twig.extension', UserExtension::class)
            ->args([
                abstract_arg('Configuration'),
            ])
            ->tag('twig.extension')

        // Mailer
        ->set('ekyna_user.mailer', UserMailer::class)
            ->args([
                service('ekyna_setting.manager'),
                service('translator'),
                service('twig'),
                service('router'),
                service('mailer'),
                abstract_arg('Configuration'),
            ])
    ;
};
