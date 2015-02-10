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
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekyna_user');

        $rootNode
            ->children()
                ->arrayNode('templates')
                    ->children()
                        ->scalarNode('base')->defaultValue('EkynaUserBundle::base.html.twig')->end()
                        ->scalarNode('email')->defaultValue('EkynaUserBundle::email.html.twig')->end()
                        ->scalarNode('address')->defaultValue('EkynaUserBundle:Address:_render.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('account')
                    ->children()
                        ->booleanNode('enable')->defaultValue(false)->end()
                        ->scalarNode('prefix')->defaultValue('/account')->end()
                        ->booleanNode('username')->defaultValue(false)->end()
                        ->booleanNode('register')->defaultValue(false)->end()
                        ->booleanNode('resetting')->defaultValue(false)->end()
                        ->booleanNode('profile')->defaultValue(false)->end()
                        ->booleanNode('address')->defaultValue(false)->end()
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
                            ->isRequired()
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
                            ->isRequired()
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
                            ->isRequired()
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
