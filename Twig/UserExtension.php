<?php

namespace Ekyna\Bundle\UserBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class UserExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserExtension extends AbstractExtension
{
    /**
     * @var array
     */
    protected $config;


    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'ekyna_user_account_var',
                [$this, 'getAccountVar']
            ),
        ];
    }

    /**
     * Returns the account var.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAccountVar($key)
    {
        if (!isset($this->config['account'][$key])) {
            throw new \InvalidArgumentException("Account var '$key' is not defined.");
        }

        return $this->config['account'][$key];
    }
}
