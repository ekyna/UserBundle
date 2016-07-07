<?php

namespace Ekyna\Bundle\UserBundle\Twig;

use Ekyna\Bundle\UserBundle\Helper\IdentityHelper;
use Ekyna\Bundle\UserBundle\Model\IdentityInterface;

/**
 * Class UserExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserExtension extends \Twig_Extension
{
    /**
     * @var IdentityHelper
     */
    protected $helper;

    /**
     * @var array
     */
    protected $config;


    /**
     * Constructor.
     *
     * @param IdentityHelper $helper
     * @param array          $config
     */
    public function __construct(IdentityHelper $helper, array $config)
    {
        $this->helper = $helper;
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
    public function getFunctions()
    {
        return [
            // TODO remove
            new \Twig_SimpleFunction('render_identity', [$this, 'renderIdentity'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('gender', [$this, 'getGenderLabel']),
            new \Twig_SimpleFilter('identity', [$this, 'renderIdentity'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Renders the identity.
     *
     * @param IdentityInterface $identity
     * @param bool              $long
     *
     * @return string
     */
    public function renderIdentity(IdentityInterface $identity, $long = false)
    {
        return $this->helper->renderIdentity($identity, $long);
    }

    /**
     * Returns the gender label.
     *
     * @param string $gender
     * @param bool   $long
     *
     * @return string
     */
    public function getGenderLabel($gender, $long = false)
    {
        return $this->helper->getGenderLabel($gender, $long);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user';
    }
}
