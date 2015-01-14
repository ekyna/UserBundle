<?php

namespace Ekyna\Bundle\UserBundle\Twig;

/**
 * Class AccountExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AccountExtension extends \Twig_Extension
{
    const DEFAULT_BASE_TEMPLATE = 'EkynaUserBundle::base.html.twig';

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = array_merge(array(
            'base_template' => self::DEFAULT_BASE_TEMPLATE,
        ), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $twig)
    {
        //try {
            $twig->loadTemplate($this->options['base_template']);
        //} catch(\Exception $e) {
        //    $this->options['base_template'] = self::DEFAULT_BASE_TEMPLATE;
        //}
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ekyna_user_account_base_template', array($this, 'getBaseTemplate'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns the user account base template name.
     *
     * @return string
     */
    public function getBaseTemplate()
    {
        return $this->options['base_template'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_account';
    }
}
