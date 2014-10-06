<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GroupType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GroupType extends ResourceFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', null, array(
                'label' => 'ekyna_core.field.name'
            ))
            ->add('roles', 'choice', array(
                'label' => 'ekyna_core.field.roles',
                'expanded' => true,
                'multiple' => true,
                'choices' => array(
            	    'ROLE_ALLOWED_TO_SWITCH' => 'ekyna_core.auth.allowed_to_switch',
            	    'ROLE_ADMIN'             => 'ekyna_core.auth.admin',
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_user_group';
    }
}
