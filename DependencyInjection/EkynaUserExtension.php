<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ekyna\Bundle\ResourceBundle\DependencyInjection\AbstractExtension;

/**
 * Class EkynaUserExtension
 * @package Ekyna\Bundle\UserBundle\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUserExtension extends AbstractExtension
{
    const DEFAULT_GENDER_CLASS = 'Ekyna\Bundle\CommerceBundle\Model\Genders';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configure($configs, 'ekyna_user', new Configuration(), $container);

//        $container->setParameter('ekyna_user.gender_class', $config['gender_class']);
        $container->setParameter('ekyna_user.notification_config', $config['notification']);

        $exposedConfig = [];
        $exposedConfig['account'] = $config['account'];
//        $exposedConfig['templates'] = $config['templates'];
//        $exposedConfig['gender_class'] = $config['gender_class'];

        $container->setParameter('ekyna_user.config', $exposedConfig);
    }
}
