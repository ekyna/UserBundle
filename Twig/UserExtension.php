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
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'ekyna_user_template_name',
                [$this, 'getTemplateName']
            ),
            new \Twig_SimpleFunction(
                'ekyna_user_account_var',
                [$this, 'getAccountVar']
            ),
        ];
    }

    /**
     * Returns the template name.
     *
     * @param string $key
     *
     * @return string
     */
    public function getTemplateName($key)
    {
        if (!isset($this->config['templates'][$key])) {
            throw new \InvalidArgumentException("Template name '$key' is not defined.");
        }

        return $this->config['templates'][$key];
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user';
    }
}
