<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class GenderType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GenderType extends AbstractType
{
    /**
     * @var string
     */
    private $genderClass;


    /**
     * Constructor.
     *
     * @param string $genderClass
     */
    public function __construct($genderClass)
    {
        $this->genderClass = $genderClass;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'ekyna_core.field.gender',
            'expanded' => true,
            'choices' => call_user_func($this->genderClass.'::getChoices'),
            'attr' => array(
            	'class' => 'inline no-select2',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_gender';
    }
}
