<?php

namespace Ekyna\Bundle\UserBundle\Twig;

/**
 * Class UserExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $config;


    /**
     * Constructor.
     *
     * @param array          $config
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
            new \Twig_SimpleFunction(
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
