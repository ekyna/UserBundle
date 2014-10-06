<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProfileType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ProfileType extends ProfileFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('company', 'text', array(
                'label' => 'ekyna_core.field.company',
                'required' => false
            ))
            ->add('gender', 'ekyna_user_gender')
            ->add('firstname', 'text', array(
                'label' => 'ekyna_core.field.firstname',
            ))
            ->add('lastname', 'text', array(
                'label' => 'ekyna_core.field.lastname',
            ))
            ->add('phone', 'text', array(
                'label' => 'ekyna_core.field.phone',
                'required' => false
            ))
            ->add('mobile', 'text', array(
                'label' => 'ekyna_core.field.mobile',
                'required' => false
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_profile';
    }
}
