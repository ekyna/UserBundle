<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\DependencyInjection;

use Ekyna\Bundle\ResourceBundle\DependencyInjection\PrependBundleConfigTrait;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\User\Service\OAuth\OAuthConfigurator;
use Ekyna\Component\User\Service\Security\SecurityConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Configuration as KnpuConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

use function array_keys;
use function in_array;

/**
 * Class EkynaUserExtension
 * @package Ekyna\Bundle\UserBundle\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUserExtension extends Extension implements PrependExtensionInterface
{
    use PrependBundleConfigTrait;

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependBundleConfigFiles($container);

        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->prependSecurity($config, $container);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services/console.php');
        $loader->load('services/controller.php');
        $loader->load('services/form.php');
        $loader->load('services/menu.php');
        $loader->load('services/security.php');
        $loader->load('services.php');

        if (in_array($container->getParameter('kernel.environment'), ['dev', 'test'], true)) {
            $loader->load('services/dev.php');
        }

        $this->configureAccount($config['account'], $container);
        $this->configureSecurity($config['security'], $container);
    }

    private function configureAccount(array $config, ContainerBuilder $container): void
    {
        // Routing loader
        $container
            ->getDefinition('ekyna_user.routing.account_loader')
            ->replaceArgument(0, $config);

        // Twig extension
        $container
            ->getDefinition('ekyna_user.twig.extension')
            ->replaceArgument(0, $config); // TODO check

        // Mailer
        $container
            ->getDefinition('ekyna_user.mailer')
            ->replaceArgument(5, $config); // TODO check

        // Menu builder
        $container
            ->getDefinition('ekyna_user.menu_builder')
            ->replaceArgument(4, $config);

        // Profile controller
        $container
            ->getDefinition('ekyna_user.controller.account.profile')
            ->replaceArgument(6, $config['profile']);

        // Registration controller
        $container
            ->getDefinition('ekyna_user.controller.account.registration')
            ->replaceArgument(9, $config['registration']);

        // Registration form
        $container
            ->getDefinition('ekyna_user.form_type.registration')
            ->replaceArgument(2, $config['registration']['check']);

        // Resetting controller
        $container
            ->getDefinition('ekyna_user.controller.account.resetting')
            ->replaceArgument(8, $config['resetting']);
    }

    private function configureSecurity(array $config, ContainerBuilder $container): void
    {
        // Security controller
        $container
            ->getDefinition('ekyna_user.controller.security')
            ->replaceArgument(5, [
                'template'    => '@EkynaUser/Security/login.html.twig',
                'remember_me' => $config['remember_me'],
                'target_path' => 'ekyna_user_account_index',
            ]);

        // Token repository
        $container
            ->getDefinition('ekyna_user.repository.token')
            ->addMethodCall('configureExpiration', [$config['token_expiration']]);

        // Token manager
        $container
            ->getDefinition('ekyna_user.manager.token')
            ->addMethodCall('configureExpiration', [$config['token_expiration']]);
    }

    private function prependSecurity(array $config, ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig('knpu_oauth2_client');
        $knpuConfig = $this->processConfiguration(new KnpuConfiguration(), $configs);

        $customAuthenticators = ['ekyna_user.security.authenticator.form_login'];

        // OAuth authenticators
        $owners = array_keys($knpuConfig['clients']);
        foreach (array_keys(OAuthConfigurator::OWNERS) as $owner) {
            if (in_array('ekyna_user_' . $owner, $owners, true)) {
                $customAuthenticators[] = OAuthConfigurator::authenticator('ekyna_user', $owner);
            }
        }

        $configurator = new SecurityConfigurator();
        $configurator->configure($container, [
            'providers'        => [
                'ekyna_user' => [
                    'id' => 'ekyna_user.security.user_provider',
                ],
            ],
            'password_hashers' => [
                UserInterface::class => [
                    'algorithm' => 'auto',
                ],
            ],
            'firewalls'        => [
                'main' => [
                    'provider'              => 'ekyna_user',
                    'custom_authenticators' => $customAuthenticators,
                    'login_throttling'      => [
                        'max_attempts' => 5,
                        'interval'     => '5 minutes',
                    ],
                    // https://symfony.com/doc/current/security/login_link.html
                    'login_link'            => [
                        'check_route'          => 'ekyna_user_security_login_link_check',
                        'signature_properties' => ['id'],
                    ],
                    'remember_me'           => [
                        'secret'                => '%kernel.secret%',
                        'name'                  => 'USER_REMEMBER_ME',
                        'lifetime'              => 60 * 60 * 24 * 7,
                        'remember_me_parameter' => $config['security']['remember_me'],
                    ],
                    'logout'                => [
                        'path'               => 'ekyna_user_security_logout',
                        'target'             => 'ekyna_user_security_login',
                        'invalidate_session' => false,
                    ],
                    'lazy'                  => true,
                ],
            ],
        ]);
    }
}
