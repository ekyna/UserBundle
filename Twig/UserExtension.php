<?php

namespace Ekyna\Bundle\UserBundle\Twig;

use Ekyna\Bundle\UserBundle\Entity\AddressRepository;
use Ekyna\Bundle\UserBundle\Model\AddressInterface;
use Ekyna\Bundle\UserBundle\Model\IdentityInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class UserExtension
 * @package Ekyna\Bundle\UserBundle\Twig
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var AddressRepository
     */
    protected $repository;

    /**
     * @var \Twig_Template
     */
    protected $addressTemplate;


    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator
     * @param AddressRepository   $repository
     * @param array               $config
     */
    public function __construct(TranslatorInterface $translator, AddressRepository $repository, array $config)
    {
        $this->translator = $translator;
        $this->repository = $repository;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $twig)
    {
        $this->addressTemplate = $twig->loadTemplate($this->config['templates']['address']);
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return array(
            'ekyna_user_config' => $this->config,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_identity',  array($this, 'renderIdentity'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_address',   array($this, 'renderAddress'),  array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('gender', array($this, 'getGenderLabel')),
        );
    }

    /**
     * Renders the identity.
     *
     * @param IdentityInterface $identity
     * @param bool              $long
     * @return string
     */
    public function renderIdentity(IdentityInterface $identity, $long = false)
    {
        return sprintf(
            '%s %s %s',
            $this->translator->trans($this->getGenderLabel($identity->getGender(), $long)),
            $identity->getFirstName(),
            $identity->getLastName()
        );
    }

    /**
     * Renders the address.
     *
     * @param AddressInterface|int $addressOrId
     * @param bool $displayPhones
     * @return string
     */
    public function renderAddress($addressOrId, $displayPhones = true)
    {
        if ($addressOrId instanceOf AddressInterface) {
            $address = $addressOrId;
        } else {
            $addressOrId = intval($addressOrId);
            if (0 >= $addressOrId || null === $address = $this->repository->find($addressOrId)) {
                return '';
            }
        }

        return $this->addressTemplate->render(array('address' => $address, 'display_phones' => $displayPhones));
    }

    /**
     * Returns the gender label.
     *
     * @param string $gender
     * @param bool   $long
     * @return string
     */
    public function getGenderLabel($gender, $long = false)
    {
        return call_user_func($this->config['gender_class'].'::getLabel', $gender, $long);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user';
    }
}
