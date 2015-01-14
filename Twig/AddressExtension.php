<?php

namespace Ekyna\Bundle\UserBundle\Twig;

use Ekyna\Bundle\UserBundle\Entity\AddressRepository;
use Ekyna\Bundle\UserBundle\Model\AddressInterface;

/**
 * Class AddressExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressExtension extends \Twig_Extension
{
    const DEFAULT_ADDRESS_TEMPLATE = 'EkynaUserBundle:Address:_render.html.twig';

    /**
     * @var AddressRepository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Twig_Template
     */
    protected $template;

    /**
     * Constructor.
     *
     * @param AddressRepository $repository
     * @param array $options
     */
    public function __construct(AddressRepository $repository, array $options)
    {
        $this->repository = $repository;

        $this->options = array_merge(array(
            'address_template' => self::DEFAULT_ADDRESS_TEMPLATE,
        ), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $twig)
    {
        $this->template = $twig->loadTemplate($this->options['address_template']);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_address', array($this, 'renderAddress'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders the address.
     *
     * @param mixed $addressOrId
     * @return string
     */
    public function renderAddress($addressOrId)
    {
        if ($addressOrId instanceOf AddressInterface) {
            $address = $addressOrId;
        } else {
            $addressOrId = intval($addressOrId);
            if (0 >= $addressOrId || null === $address = $this->repository->find($addressOrId)) {
                return '';
            }
        }

        return $this->template->render(array('address' => $address));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_address';
    }
}
