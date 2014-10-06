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
                ->booleanNode('account_enabled')->defaultValue(false)->end()
                ->booleanNode('address_enabled')->defaultValue(false)->end()
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
                                ->scalarNode('templates')->defaultValue('EkynaUserBundle:User/Admin')->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\User')->end()
                                ->scalarNode('controller')->end()
                                ->scalarNode('repository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\UserType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\UserType')->end()
                            ->end()
                        ->end()
                        ->arrayNode('group')
                            ->isRequired()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('templates')->defaultValue('EkynaUserBundle:Group/Admin')->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\Group')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\UserBundle\Controller\Admin\GroupController')->end()
                                ->scalarNode('repository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\GroupType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\GroupType')->end()
                            ->end()
                        ->end()
                        ->arrayNode('address')
                            ->isRequired()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('templates')->defaultValue('EkynaUserBundle:Address/Admin')->end()
                                ->scalarNode('parent')->defaultValue('ekyna_user.user')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\Address')->end()
                                ->scalarNode('controller')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\UserBundle\Entity\AddressRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\AddressType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\AddressType')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
