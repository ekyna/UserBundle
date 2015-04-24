<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ExtensionPass
 * @package Ekyna\Bundle\UserBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_user.extension.registry')) {
            return;
        }

        $registry = $container->getDefinition('ekyna_user.extension.registry');

        $extensions = $container->findTaggedServiceIds('ekyna_user.extension');
        foreach ($extensions as $id => $extension) {
            $registry->addMethodCall('addExtension', array(new Reference($id)));
        }
    }
}
