<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Service\Routing;

use Ekyna\Bundle\ResourceBundle\Service\Routing\Traits\PrefixTrait;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class AccountLoader
 * @package Ekyna\Bundle\UserBundle\Service\Routing
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AccountLoader extends Loader
{
    use PrefixTrait;

    private const DIRECTORY = '@EkynaUserBundle/Resources/config/routing';

    private array $config;
    private bool  $loaded = false;


    public function __construct(array $config, string $env = null)
    {
        parent::__construct($env);

        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function load($resource, string $type = null)
    {
        if (true === $this->loaded) {
            throw new RuntimeException('Do not add the "user account" routing loader twice.');
        }

        $this->loaded = true;

        $collection = new RouteCollection();

        if (!$this->config['enabled']) {
            return $collection;
        }

        /** @var RouteCollection $routes */
        $routes = $this->import(self::DIRECTORY . '/account.yaml', 'yaml');

        $collection->addCollection($routes);

        $sections = [
            'registration'  => [
                'en' => '/registration',
                'fr' => '/inscription',
                'es' => '/registro',
            ],
            'resetting' => [
                'en' => '/resetting',
                'fr' => '/reinitialisation',
                'es' => '/reiniciar',
            ],
            'profile'   => [
                'en' => '/profile',
                'fr' => '/profil',
                'es' => '/perfil',
            ],
        ];

        foreach ($sections as $name => $prefixes) {
            if (!$this->config[$name]['enabled']) {
                continue;
            }

            /** @var RouteCollection $routes */
            $routes = $this->import(self::DIRECTORY . '/account/' . $name . '.yaml', 'yaml');

            $this->addPrefixes($routes, $prefixes);

            $collection->addCollection($routes);
        }

        $this->addPrefixes($collection, $this->config['routing_prefix']);

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function supports($resource, string $type = null): bool
    {
        return 'ekyna_user_account' === $type;
    }
}
