<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection\Compiler;

use Ekyna\Bundle\AdminBundle\Menu\MenuPool;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class AdminMenuPass
 * @package Ekyna\Bundle\UserBundle\DependencyInjection\Compiler
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AdminMenuPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(MenuPool::class)) {
            return;
        }

        $pool = $container->getDefinition(MenuPool::class);

        $pool->addMethodCall('createGroup', [[
            'name'     => 'users',
            'label'    => 'ekyna_user.user.label.plural',
            'icon'     => 'users',
            'position' => 99,
        ]]);
        $pool->addMethodCall('createEntry', ['users', [
            'name'     => 'users',
            'route'    => 'ekyna_user_user_admin_list',
            'label'    => 'ekyna_user.user.label.plural',
            'resource' => 'ekyna_user_user',
            'position' => 1,
        ]]);
        $pool->addMethodCall('createEntry', ['users', [
            'name'     => 'groups',
            'route'    => 'ekyna_user_group_admin_list',
            'label'    => 'ekyna_user.group.label.plural',
            'resource' => 'ekyna_user_group',
            'position' => 2,
        ]]);
    }
}
