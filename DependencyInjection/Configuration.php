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
                ->arrayNode('account')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultFalse()->end()
                        ->booleanNode('username')->defaultFalse()->end()
                        ->booleanNode('register')->defaultFalse()->end()
                        ->booleanNode('resetting')->defaultFalse()->end()
                        ->booleanNode('profile')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('notification')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('admin_login')->defaultTrue()->end()
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
                                ->variableNode('templates')->defaultValue([
                                    '_form.html' => '@EkynaUser/Admin/User/_form.html',
                                    'show.html'  => '@EkynaUser/Admin/User/show.html',
                                ])->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\User')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\UserBundle\Controller\Admin\UserController')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\UserBundle\Repository\UserRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\UserType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\UserType')->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('event')->end()
                            ->end()
                        ->end()
                        ->arrayNode('group')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue([
                                    '_form.html' => '@EkynaUser/Admin/Group/_form.html',
                                    'show.html'  => '@EkynaUser/Admin/Group/show.html',
                                ])->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\UserBundle\Entity\Group')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\UserBundle\Controller\Admin\GroupController')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\UserBundle\Repository\GroupRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\UserBundle\Form\Type\GroupType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\UserBundle\Table\Type\GroupType')->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('event')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
