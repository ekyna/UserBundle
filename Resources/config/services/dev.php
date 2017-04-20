<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UserBundle\Service\Fixtures\UserProcessor;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // User processor
        ->set('ekyna_user.fixtures.admin_processor', UserProcessor::class)
            ->args([
                service('security.password_encoder'),
            ])
            ->tag('fidry_alice_data_fixtures.processor')
    ;
};
