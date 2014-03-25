<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * GroupType
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
                'label' => 'Nom'
            ))
            ->add('roles', 'choice', array(
                'label' => 'Roles',
                'expanded' => true,
                'multiple' => true,
                'choices' => array(
            	    'ROLE_ALLOWED_TO_SWITCH' => 'Authorisé à switcher',
            	    'ROLE_ADMIN'             => 'Administrateur',
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
    	return 'ekyna_group';
    }
}