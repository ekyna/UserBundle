<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AdminMenuPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_admin.menu.pool')) {
            return;
        }

        $pool = $container->getDefinition('ekyna_admin.menu.pool');

        $pool->addMethodCall('createGroupReference', array(
            'config', 'ekyna_core.field.config', 'cogs', null, 99
        ));
        $pool->addMethodCall('createEntryReference', array(
            'config', 'users', 'ekyna_user_user_admin_home', 'ekyna_user.user.label.plural'
        ));
        $pool->addMethodCall('createEntryReference', array(
            'config', 'groups', 'ekyna_user_group_admin_home', 'ekyna_user.group.label.plural'
        ));
    }
}