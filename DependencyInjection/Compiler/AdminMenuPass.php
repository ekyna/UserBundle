<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * AdminMenuPass
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AdminMenuPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_admin.menu.pool')) {
            return;
        }

        $pool = $container->getDefinition('ekyna_admin.menu.pool');

        $pool->addMethodCall('createGroup', array(array(
            'name'     => 'users',
            'label'    => 'ekyna_user.user.label.plural',
            'icon'     => 'users',
            'position' => 98,
        )));
        $pool->addMethodCall('createEntry', array('users', array( 
            'name'     => 'users',
            'route'    => 'ekyna_user_user_admin_home',
            'label'    => 'ekyna_user.user.label.plural',
            'resource' => 'ekyna_user_user',
        )));
        $pool->addMethodCall('createEntry', array('users', array(
            'name'     => 'groups', 
            'route'    => 'ekyna_user_group_admin_home', 
            'label'    => 'ekyna_user.group.label.plural',
            'resource' => 'ekyna_user_group',
        )));
    }
}