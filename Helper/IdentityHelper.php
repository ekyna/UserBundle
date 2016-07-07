<?php

namespace Ekyna\Bundle\UserBundle\Helper;

use Ekyna\Bundle\UserBundle\Model\IdentityInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class IdentityHelper
 * @package Ekyna\Bundle\UserBundle\Helper
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class IdentityHelper
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $genderClass;


    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator
     * @param string              $genderClass
     */
    public function __construct(TranslatorInterface $translator, $genderClass)
    {
        $this->translator = $translator;
        $this->genderClass = $genderClass;
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
        return sprintf(
            '%s %s %s',
            $this->translator->trans($this->getGenderLabel($identity->getGender(), $long)),
            $identity->getFirstName(),
            $identity->getLastName()
        );
    }

    /**
     * Returns the gender label.
     *
     * @param string $gender
     * @param bool $long
     *
     * @return mixed
     */
    public function getGenderLabel($gender, $long = false)
    {
        return call_user_func($this->genderClass.'::getLabel', $gender, $long);
    }
}
