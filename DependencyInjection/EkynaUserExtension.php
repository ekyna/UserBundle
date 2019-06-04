<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ekyna\Bundle\ResourceBundle\DependencyInjection\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class EkynaUserExtension
 * @package Ekyna\Bundle\UserBundle\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUserExtension extends AbstractExtension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configure($configs, 'ekyna_user', new Configuration(), $container);

        $container->setParameter('ekyna_user.config', [
            'account' => $config['account']
        ]);

        if (in_array($container->getParameter('kernel.environment'), ['dev', 'test'], true)) {
            $loader = new XmlFileLoader($container, new FileLocator($this->getConfigurationDirectory()));
            $loader->load('services_dev_test.xml');
        }
    }
}
