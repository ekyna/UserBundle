<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * GroupType
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GroupType extends AbstractType
{
    protected $dataClass;

    public function __construct($class)
    {
        $this->dataClass = $class;
    }

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_user_group';
    }
}
