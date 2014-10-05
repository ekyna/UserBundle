<?php

namespace Ekyna\Bundle\UserBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class AccountLoader
 * @package Ekyna\Bundle\UserBundle\Routing
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AccountLoader extends Loader
{
    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @var bool
     */
    private $accountEnabled = false;

    /**
     * @var bool
     */
    private $addressEnabled = false;

    /**
     * Constructor.
     *
     * @param bool $accountEnabled
     * @param bool $addressEnabled
     */
    public function __construct($accountEnabled, $addressEnabled)
    {
        $this->accountEnabled = $accountEnabled;
        $this->addressEnabled = $addressEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "product" routes loader twice.');
        }

        $prefix = '/account';

        $collection = new RouteCollection();

        // Base account
        if ($this->accountEnabled) {
            $resource = '@EkynaUserBundle/Resources/config/routing/account.yml';
            $type     = 'yaml';
            $routes = $this->import($resource, $type);
            $routes->addPrefix($prefix);
            $collection->addCollection($routes);

            // Address
            if ($this->addressEnabled) {
                $resource = '@EkynaUserBundle/Resources/config/routing/account/address.yml';
                $type     = 'yaml';
                $routes = $this->import($resource, $type);
                $routes->addPrefix($prefix.'/address');
                $collection->addCollection($routes);
            }
        }

        $this->loaded = true;

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'account' === $type;
    }
}
