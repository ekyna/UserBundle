<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'ekyna_core.field.gender',
            'expanded' => true,
            'choices' => call_user_func($this->genderClass.'::getChoices'),
            'attr' => [
            	'class' => 'inline no-select2',
            ],
        ]);
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
