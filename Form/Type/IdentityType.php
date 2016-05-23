<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class IdentityType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class IdentityType extends AbstractType
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', GenderType::class, [
                'label'          => false,
                'expanded'       => false,
                'required'       => $options['required'],
                'choices'        => $options['gender_choices'],
                'placeholder'    => 'ekyna_core.value.choose',
                'error_bubbling' => true,
            ])
            ->add('lastName', TextType::class, [
                'label'          => false,
                'required'       => $options['required'],
                'attr'           => [
                    'placeholder' => 'ekyna_core.field.last_name',
                ],
                'error_bubbling' => true,
            ])
            ->add('firstName', TextType::class, [
                'label'          => false,
                'required'       => $options['required'],
                'attr'           => [
                    'placeholder' => 'ekyna_core.field.first_name',
                ],
                'error_bubbling' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class'     => 'Ekyna\Bundle\UserBundle\Model\IdentityInterface',
                'gender_choices' => call_user_func($this->genderClass . '::getChoices'),
                'label'          => 'ekyna_core.field.identity',
                'inherit_data'   => true,
                'required'       => true,
                'error_bubbling' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_user_identity';
    }
}
