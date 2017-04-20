<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\DependencyInjection;

use Ekyna\Bundle\UserBundle\Entity\Token;
use Ekyna\Bundle\UserBundle\Form\Type\ProfileType;
use Ekyna\Bundle\UserBundle\Form\Type\RegistrationType;
use Ekyna\Bundle\UserBundle\Form\Type\ResettingType;
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
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('ekyna_user');

        $root = $builder->getRootNode();

        $this->addAccountSection($root);
        $this->addSecuritySection($root);

        return $builder;
    }

    /**
     * Adds the `account` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addAccountSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('account')
                    ->canBeDisabled()
                    ->children()
                        ->arrayNode('registration')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('form')
                                    ->defaultValue(RegistrationType::class)
                                    ->cannotBeEmpty()
                                ->end()
                                ->arrayNode('template')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('email')
                                            ->defaultValue('@EkynaUser/Account/Registration/email.html.twig')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('check')
                                            ->defaultValue('@EkynaUser/Account/Registration/check.html.twig')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('register')
                                            ->defaultValue('@EkynaUser/Account/Registration/register.html.twig')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('confirmed')
                                            ->defaultValue('@EkynaUser/Account/Registration/confirmed.html.twig')
                                            ->cannotBeEmpty()
                                        ->end()
                                    ->end()
                                ->end()
                                ->scalarNode('email')
                                    ->defaultValue('@EkynaUser/Email/registration_check.html.twig')
                                    ->cannotBeEmpty()
                                ->end()
                                ->booleanNode('check')
                                    ->defaultFalse()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('resetting')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('form')
                                    ->defaultValue(ResettingType::class)
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('template')
                                    ->defaultValue('@EkynaUser/Account/Resetting/reset.html.twig')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('email')
                                    ->defaultValue('@EkynaUser/Email/resetting_check.html.twig')
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('profile')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('form')
                                    ->defaultValue(ProfileType::class)
                                    ->cannotBeEmpty()
                                ->end()
                                ->arrayNode('template')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('index')
                                            ->defaultValue('@EkynaUser/Account/Profile/index.html.twig')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('edit')
                                            ->defaultValue('@EkynaUser/Account/Profile/edit.html.twig')
                                            ->cannotBeEmpty()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Adds the `security` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addSecuritySection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('security')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('remember_me')
                            ->defaultValue('_user_remember_me')
                            ->cannotBeEmpty()
                        ->end()
                        ->arrayNode('token_expiration')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode(Token::TYPE_REGISTRATION)
                                    ->defaultValue('1 hour')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode(Token::TYPE_RESETTING)
                                    ->defaultValue('10 mins')
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
