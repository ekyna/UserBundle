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
        list($config, $loader) = $this->configure($configs, 'ekyna_user', new Configuration(), $container);

        $accountEnabled = $config['account_enabled'];
        $addressEnabled = $config['address_enabled'];
        $container->setParameter('ekyna_user.account_enabled', $accountEnabled);
        $container->setParameter('ekyna_user.address_enabled', $addressEnabled);

        $menu = $container->getDefinition('ekyna_user.menu_builder');
        if ($accountEnabled) {
            $menu->addMethodCall('addAccountEntry', array('profil', array(
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
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
            	case 'fos_user':
            	    $container->prependExtensionConfig(
            	       $name,
            	       $this->getFosUserBundleConfiguration()
                    );
            	    break;

            	case 'fos_elastica':
            	    $container->prependExtensionConfig(
            	        $name,
            	        $this->getFosElasticaBundleConfiguration()
                    );
            	    break;

            	case 'jms_serializer':
            	    $container->prependExtensionConfig(
            	        $name,
            	        $this->getJmsSerializerBundleConfiguration()
                    );
            	    break;
            }
        }
    }

    /**
     * Returns FOSUserBundle configuration.
     *
     * @return array
     */
    protected function getFosUserBundleConfiguration()
    {
        return array(
            'db_driver' => 'orm',
            'firewall_name' => 'admin',
            'user_class' => 'Ekyna\Bundle\UserBundle\Entity\User',
            'group' => array(
                'group_class' => 'Ekyna\Bundle\UserBundle\Entity\Group',
            ),
            'service' => array(
                'mailer' => 'ekyna_user.mailer.default'
            ),
            'profile' => array(
            	'form' => array(
            	    'type' => 'ekyna_user_profile',
                ),
            ),
            'registration' => array(
            	'form' => array(
            	    'type' => 'ekyna_user_registration',
                ),
            ),
        );
    }

    /**
     * Returns JMSSerializerBundle configuration.
     *
     * @return array
     */
    protected function getJmsSerializerBundleConfiguration()
    {
        return array(
            'metadata' => array(
                'directories' => array(
                    'FOSUserBundle' => array(
                        'namespace_prefix' => 'FOS\\UserBundle',
                        'path' => realpath(__DIR__.'/../Resources/serializer/FOSUserBundle'),
                    ),
                ),
            ),
        );
    }

    /**
     * Returns FOSElasticaBundle configuration.
     *
     * @return array
     */
    protected function getFosElasticaBundleConfiguration()
    {
        return array(
            'indexes' => array(
                'search' => array(
                    'types' => array(
                        'ekyna_user_user' => array(
                            'mappings' => array(
                                'username' => array('search_analyzer' => 'custom_search', 'index_analyzer' => 'custom_index'),
                                'firstname' => array('search_analyzer' => 'custom_search', 'index_analyzer' => 'custom_index'),
                                'lastname' => array('search_analyzer' => 'custom_search', 'index_analyzer' => 'custom_index'),
                                'email' => array('search_analyzer' => 'custom_search', 'index_analyzer' => 'custom_index'),
                            ),
                            'persistence' => array(
                                'driver' => 'orm',
                                'model' => 'Ekyna\Bundle\UserBundle\Entity\User',
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
        );
    }
}
