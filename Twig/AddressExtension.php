<?php

namespace Ekyna\Bundle\UserBundle\Twig;

use Ekyna\Bundle\UserBundle\Entity\AddressRepository;
use Ekyna\Bundle\UserBundle\Entity\Address;

/**
 * AddressExtension
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressExtension extends \Twig_Extension
{
    /**
     * @var AddressRepository
     */
    protected $repository;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $template;

    /**
     * Initialize AddressExtension
     * @param AddressRepository $repository
     * @param \Twig_Environment $twig
     * @param string $template
     */
    public function __construct(AddressRepository $repository, \Twig_Environment $twig, $template)
    {
        $this->repository = $repository;
        $this->twig = $twig;
        $this->template = $template;
    }

    /**
     * Returns a list of functions to add to the existing list.
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_address', array($this, 'renderAddress'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Format an address
     *
     * @param mixed $addressOrId
     * @return string
     */
    public function renderAddress($addressOrId)
    {
        if ($addressOrId instanceOf Address) {
            $address = $addressOrId;
        } else {
            if (null === $address = $this->repository->findOneBy(array('id', intval($addressOrId)))) {
                return '';
            }
        }

        return $this->twig->render($this->template, array('address' => $address));
    }

    /**
     * Returns the name of the extension.
     * @return string The extension name
     */
    public function getName()
    {
        return 'ekyna_address';
    }
}
