<?php

namespace Ekyna\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ekyna\Bundle\UserBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Configuration implements ConfigurationInterface
{
    const DEFAULT_GENDER_CLASS = 'Ekyna\Bundle\UserBundle\Model\Genders';

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekyna_user');

        $rootNode
            ->children()
                ->scalarNode('gender_class')
                    ->validate()
                        ->ifTrue(function ($class) {
                            if (!class_exists($class)) {
                                return true;
                            }
                            if ($class !== self::DEFAULT_GENDER_CLASS &&
                                !in_array(self::DEFAULT_GENDER_CLASS, class_parents($class))) {
                                return true;
                            }
                            return false;
                        })
                        ->thenInvalid('%s must extend '.self::DEFAULT_GENDER_CLASS)
                    ->end()
                    ->defaultValue(self::DEFAULT_GENDER_CLASS)
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base')->defaultValue('EkynaUserBundle::base.html.twig')->end()
                        ->scalarNode('email')->defaultValue('EkynaUserBundle::email.html.twig')->end()
                        ->scalarNode('address')->defaultValue('EkynaUserBundle:Address:_render.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('account')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultValue(false)->end()
                        ->booleanNode('username')->defaultValue(false)->end()
                        ->booleanNode('register')->defaultValue(false)->end()
                        ->booleanNode('resetting')->defaultValue(false)->end()
                        ->booleanNode('profile')->defaultValue(false)->end()
                        ->booleanNode('address')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('notification')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('admin_login')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end()
        ;

        $this->addPoolsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds `pools` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addPoolsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('pools')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('user')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue(array(
                                    '_form.html' => 'EkynaUserBundle:Admin/User:_form.html',
                                    'show.html'  => 'EkynaUserBundle:Admin/User:show.html',
                                ))->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\User')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\UserBundle\Controller\Admin\UserController')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\UserBundle\Entity\UserRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\UserType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\UserType')->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('event')->defaultValue('Ekyna\Bundle\UserBundle\Event\UserEvent')->end()
                            ->end()
                        ->end()
                        ->arrayNode('group')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue(array(
                                    '_form.html' => 'EkynaUserBundle:Admin/Group:_form.html',
                                    'show.html'  => 'EkynaUserBundle:Admin/Group:show.html',
                                ))->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\Group')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\UserBundle\Controller\Admin\GroupController')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\UserBundle\Entity\GroupRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\GroupType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\GroupType')->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('event')->end()
                            ->end()
                        ->end()
                        ->arrayNode('address')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue('EkynaUserBundle:Admin/Address')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\Address')->end()
                                ->scalarNode('controller')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\UserBundle\Entity\AddressRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\AddressType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\AddressType')->end()
                                ->scalarNode('parent')->defaultValue('ekyna_user.user')->end()
                                ->scalarNode('event')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
