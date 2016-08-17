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
    const DEFAULT_GENDER_CLASS = 'Ekyna\Bundle\UserBundle\Model\Genders';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configure($configs, 'ekyna_user', new Configuration(), $container);

        $container->setParameter('ekyna_user.gender_class', $config['gender_class']);
        $container->setParameter('ekyna_user.notification_config', $config['notification']);

        $accountConfig = $config['account'];

        $menu = $container->getDefinition('ekyna_user.menu_builder');
        if ($accountConfig['enable']) {
            if ($accountConfig['profile']) {
                $menu->addMethodCall('addAccountEntry', ['profile', [
                    'label'    => 'ekyna_user.account.menu.profile',
                    'route'    => 'fos_user_profile_show',
                    'position' => -3,
                ]]);
            }
            $menu->addMethodCall('addAccountEntry', ['password', [
                'label'    => 'ekyna_user.account.menu.password',
                'route'    => 'fos_user_change_password',
                'position' => -2,
            ]]);
        }

        $exposedConfig = [];
        $exposedConfig['account'] = $config['account'];
        $exposedConfig['templates'] = $config['templates'];
        $exposedConfig['gender_class'] = $config['gender_class'];

        $container->setParameter('ekyna_user.config', $exposedConfig);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        parent::prepend($container);

        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('JMSSerializerBundle', $bundles)) {
            $this->configureJMSSerializerBundle($container);
        }
    }

    /**
     * Configures the JMS serializer bundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureJMSSerializerBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('jms_serializer', [
            'metadata' => [
                'directories' => [
                    'FOSUserBundle' => [
                        'namespace_prefix' => 'FOS\\UserBundle',
                        'path'             => realpath(__DIR__ . '/../Resources/serializer/FOSUserBundle'),
                    ],
                ],
            ],
        ]);
    }
}
