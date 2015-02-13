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
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('gender', 'ekyna_user_gender', array(
                'label' => false,
                'expanded' => false,
            ))
            ->add('lastName', 'text', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'ekyna_core.field.last_name',
                ),
            ))
            ->add('firstName', 'text', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'ekyna_core.field.first_name',
                ),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Ekyna\Bundle\UserBundle\Model\IdentityInterface',
                'label' => 'ekyna_core.field.identity',
                'inherit_data' => true,
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