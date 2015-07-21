<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class IdentityType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class IdentityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', 'ekyna_user_gender', array(
                'label'    => false,
                'expanded' => false,
                'required' => $options['required'],
                'empty_value' => 'ekyna_core.value.choose',
                'error_bubbling' => true,
            ))
            ->add('lastName', 'text', array(
                'label'    => false,
                'required' => $options['required'],
                'attr'     => array(
                    'placeholder' => 'ekyna_core.field.last_name',
                ),
                'error_bubbling' => true,
            ))
            ->add('firstName', 'text', array(
                'label'    => false,
                'required' => $options['required'],
                'attr'     => array(
                    'placeholder' => 'ekyna_core.field.first_name',
                ),
                'error_bubbling' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class'   => 'Ekyna\Bundle\UserBundle\Model\IdentityInterface',
                'label'        => 'ekyna_core.field.identity',
                'inherit_data' => true,
                'required'     => true,
                'error_bubbling' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_identity';
    }
}
