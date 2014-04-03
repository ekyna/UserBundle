<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
// use Symfony\Component\Config\FileLocator;
// use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Ekyna\Bundle\AdminBundle\DependencyInjection\AbstractExtension;

/**
 * EkynaUserExtension
 */
class EkynaUserExtension extends AbstractExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->configure($configs, new Configuration(), $container);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $config = array(
            'db_driver' => 'orm',
            'firewall_name' => 'admin',
            'user_class' => 'Ekyna\Bundle\UserBundle\Entity\User',
            'group' => array(
                'group_class' => 'Ekyna\Bundle\UserBundle\Entity\Group',
            ),
            'service' => array(
        	    'user_manager' => 'ekyna_user.user_manager',
            ),
            'profile' => array(
            	'form' => array(
            	    'type' => 'ekyna_user_profile',
                ),
            ),
        );
        if (true === isset($bundles['FOSUserBundle'])) {
            $this->configureFosUserBundle($container, $config);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @return void
     */
    protected function configureFosUserBundle(ContainerBuilder $container, array $config)
    {
        foreach (array_keys($container->getExtensions()) as $name) {
            if ($name == 'fos_user') {
                $container->prependExtensionConfig($name, $config);
                break;
            }
        }
    }
}
