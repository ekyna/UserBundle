<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection\Compiler;

use Ekyna\Bundle\UserBundle\Service\Security\LogoutHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class SecurityPass
 * @package Ekyna\Bundle\UserBundle\DependencyInjection\Compiler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SecurityPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('security.logout_listener')) {
            return;
        }

        // Register the logout handler
        $definition = $container->getDefinition('security.logout_listener');
        $definition->addMethodCall('addHandler', [new Reference(LogoutHandler::class)]);
    }
}
