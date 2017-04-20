<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle;

use Ekyna\Component\User\DependencyInjection\OAuthPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EkynaUserBundle
 * @package Ekyna\Bundle\UserBundle
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUserBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new OAuthPass('ekyna_user', 'ekyna_user.security.oauth_passport_generator', [
            'target_route' => 'ekyna_user_account_index',
            'create_user'  => true,
        ]));
    }
}
