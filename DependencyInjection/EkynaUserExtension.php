<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Ekyna\Bundle\AdminBundle\DependencyInjection\AbstractExtension;

/**
 * Class EkynaUserExtension
 * @package Ekyna\Bundle\UserBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUserExtension extends AbstractExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configure($configs, 'ekyna_user', new Configuration(), $container);

        $container->setParameter('ekyna_user.username_enabled', $config['username_enabled']);

        $accountEnabled = $config['account_enabled'];
        $addressEnabled = $config['address_enabled'];
        $container->setParameter('ekyna_user.account_enabled', $accountEnabled);
        $container->setParameter('ekyna_user.address_enabled', $addressEnabled);

        $menu = $container->getDefinition('ekyna_user.menu_builder');
        if ($accountEnabled) {
            $menu->addMethodCall('addAccountEntry', array('profile', array(
                'label' => 'ekyna_user.account.menu.profile',
                'route' => 'fos_user_profile_show',
                'position' => -3,
            )));
            $menu->addMethodCall('addAccountEntry', array('password', array(
                'label' => 'ekyna_user.account.menu.password',
                'route' => 'fos_user_change_password',
                'position' => -2,
            )));
            if ($addressEnabled) {
                $menu->addMethodCall('addAccountEntry', array('address', array(
                    'label'    => 'ekyna_user.account.menu.address',
                    'route'    => 'ekyna_user_address_list',
                    'position' => -1,
                )));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('FOSUserBundle', $bundles)) {
            $this->configureFOSUserBundle($container);
        }
        if (array_key_exists('FOSElasticaBundle', $bundles)) {
            $this->configureFOSElasticaBundle($container);
        }
        if (array_key_exists('JMSSerializerBundle', $bundles)) {
            $this->configureJMSSerializerBundle($container);
        }
    }

    /**
     * Configures the FOS user bundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureFOSUserBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('fos_user', array(
            'db_driver' => 'orm',
            'firewall_name' => 'admin',
            'user_class' => '%ekyna_user.user.class%',
            'group' => array(
                'group_class' => '%ekyna_user.group.class%',
            ),
            'service' => array(
                'mailer'       => 'ekyna_user.mailer.default',
                'user_manager' => 'ekyna_user.user_manager.default',
            ),
            'profile' => array(
            	'form' => array(
            	    'type' => 'ekyna_user_profile',
                ),
            ),
            'registration' => array(
            	'confirmation' => array(
            	    'template' => 'EkynaUserBundle:Registration:email.html.twig',
                ),
            	'form' => array(
            	    'type' => 'ekyna_user_registration',
                ),
            ),
            'resetting' => array(
            	'email' => array(
            	    'template' => 'EkynaUserBundle:Resetting:email.html.twig',
                ),
            ),
        ));
    }

    /**
     * Configures the JMS serializer bundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureJMSSerializerBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('jms_serializer', array(
            'metadata' => array(
                'directories' => array(
                    'FOSUserBundle' => array(
                        'namespace_prefix' => 'FOS\\UserBundle',
                        'path' => realpath(__DIR__.'/../Resources/serializer/FOSUserBundle'),
                    ),
                ),
            ),
        ));
    }

    /**
     * Configures the FOS elastica bundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureFOSElasticaBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('fos_elastica', array(
            'indexes' => array(
                'search' => array(
                    'types' => array(
                        'ekyna_user_user' => array(
                            'mappings' => array(
                                'username' => array('search_analyzer' => 'fr_search', 'index_analyzer' => 'fr_index'),
                                'firstName' => array('search_analyzer' => 'fr_search', 'index_analyzer' => 'fr_index'),
                                'lastName' => array('search_analyzer' => 'fr_search', 'index_analyzer' => 'fr_index'),
                                'email' => array('search_analyzer' => 'fr_search', 'index_analyzer' => 'fr_index'),
                            ),
                            'persistence' => array(
                                'driver' => 'orm',
                                'model' => '%ekyna_user.user.class%',
                                'provider' => null,
                                'listener' => array(
                                    'immediate' => null,
                                ),
                                'finder' => null,
                                'repository' => 'Ekyna\Bundle\UserBundle\Search\UserRepository',
                            ),
                        ),
                    ),
                ),
            ),
        ));
    }
}
