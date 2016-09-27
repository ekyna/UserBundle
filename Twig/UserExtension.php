<?php

namespace Ekyna\Bundle\UserBundle\Twig;

/**
 * Class UserExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 * @todo remove
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
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return [
            'ekyna_user_config' => $this->config,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user';
    }
}
