<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\Command\CreateUserCommand;
use Ekyna\Bundle\UserBundle\Command\PurgeTokenCommand;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // Create user command
        ->set('ekyna_user.command.create_user', CreateUserCommand::class)
            ->args([
                service('ekyna_user.repository.user'),
                service('ekyna_user.manager.user'),
                service('ekyna_user.factory.user'),
            ])
            ->tag('console.command')

        // Purge token command
        ->set('ekyna_user.command.purge_token', PurgeTokenCommand::class)
            ->args([
                service('ekyna_user.repository.token'),
            ])
            ->tag('console.command')
    ;
};
