<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * UserType
 */
class UserType extends AbstractType
{
    protected $dataClass;

    public function __construct($class)
    {
        $this->dataClass = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('group', 'entity', array(
                'label' => 'ekyna_core.field.group',
                'class' => 'Ekyna\Bundle\UserBundle\Entity\Group',
                'property' => 'name',
            ))
            ->add('username', 'text', array(
                'label' => 'ekyna_core.field.username',
                'required' => false,
            ))
            ->add('email', 'email', array(
                'label' => 'ekyna_core.field.email',
                //'disabled' => true,
            ))
            ->add('company', 'text', array(
                'label' => 'ekyna_core.field.company',
                'required' => false
            ))
            ->add('gender', 'ekyna_user_gender', array('expanded' => false))
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
    	return 'ekyna_user_user';
    }
}
