<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\Controller\Account\ChangePasswordController;
use Ekyna\Bundle\UserBundle\Controller\Account\ProfileController;
use Ekyna\Bundle\UserBundle\Controller\Account\RegistrationController;
use Ekyna\Bundle\UserBundle\Controller\Account\ResettingController;
use Ekyna\Bundle\UserBundle\Controller\AccountController;
use Ekyna\Bundle\UserBundle\Controller\SecurityController;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // Security controller
        ->set('ekyna_user.controller.security', SecurityController::class)
            ->args([
                service('ekyna_ui.modal.renderer'),
                service('ekyna_user.manager.token'),
                service('router'),
                service('security.authentication_utils'),
                service('twig'),
                abstract_arg('Remember me field name'),
            ])
            ->alias(SecurityController::class, 'ekyna_user.controller.security')->public()

        // Account controller
        ->set('ekyna_user.controller.account', AccountController::class)
            ->args([
                service('twig'),
                service('router'),
                service('event_dispatcher'),
                service('ekyna_user.account.widget_renderer'),
                service('ekyna_user.provider.user'),
            ])
            ->alias(AccountController::class, 'ekyna_user.controller.account')->public()

        // Account profile controller
        ->set('ekyna_user.controller.account.profile', ProfileController::class)
            ->args([
                service('ekyna_user.provider.user'),
                service('form.factory'),
                service('router'),
                service('ekyna_user.manager.user'),
                service('ekyna_ui.helper.flash'),
                service('twig'),
                abstract_arg('Profile config'),
            ])
            ->alias(ProfileController::class, 'ekyna_user.controller.account.profile')->public()

        // Account change password controller
        ->set('ekyna_user.controller.account.change_password', ChangePasswordController::class)
            ->args([
                service('ekyna_user.provider.user'),
                service('form.factory'),
                service('router'),
                service('ekyna_user.manager.user'),
                service('ekyna_ui.helper.flash'),
                service('twig'),
            ])
            ->alias(ChangePasswordController::class, 'ekyna_user.controller.account.change_password')->public()

        // Account registration controller
        ->set('ekyna_user.controller.account.registration', RegistrationController::class)
            ->args([
                service('form.factory'),
                service('router'),
                service('event_dispatcher'),
                service('ekyna_user.manager.token'),
                service('ekyna_user.factory.user'),
                service('ekyna_user.manager.user'),
                service('ekyna_user.provider.user'),
                service('ekyna_user.mailer'),
                service('twig'),
                abstract_arg('Registration config'),
            ])
            ->alias(RegistrationController::class, 'ekyna_user.controller.account.registration')->public()

        // Account resetting controller
        ->set('ekyna_user.controller.account.resetting', ResettingController::class)
            ->args([
                service('form.factory'),
                service('router'),
                service('ekyna_user.manager.token'),
                service('ekyna_user.repository.user'),
                service('ekyna_user.manager.user'),
                service('ekyna_user.mailer'),
                service('ekyna_ui.helper.flash'),
                service('twig'),
                abstract_arg('Resetting config'),
            ])
            ->alias(ResettingController::class, 'ekyna_user.controller.account.resetting')->public()
    ;
};
