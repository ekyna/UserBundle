<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\Form\Type\ChangePasswordType;
use Ekyna\Bundle\UserBundle\Form\Type\ProfileType;
use Ekyna\Bundle\UserBundle\Form\Type\RegistrationType;
use Ekyna\Bundle\UserBundle\Form\Type\ResettingType;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // Account profile form type
        ->set('ekyna_user.form_type.profile', ProfileType::class)
            ->args([
                param('ekyna_user.class.user'),
            ])
            ->tag('form.type')

        // Account registration form type
        ->set('ekyna_user.form_type.registration', RegistrationType::class)
            ->args([
                service('event_dispatcher'),
                param('ekyna_user.class.user'),
                abstract_arg('Registration check flag'),
            ])
            ->tag('form.type')

        // Account resetting form type
        ->set('ekyna_user.form_type.resetting', ResettingType::class)
            ->args([
                param('ekyna_user.class.user'),
            ])
            ->tag('form.type')

        // Change password form type
        ->set('ekyna_user.form_type.change_password', ChangePasswordType::class)
            ->args([
                param('ekyna_user.class.user'),
            ])
            ->tag('form.type')
    ;
};
