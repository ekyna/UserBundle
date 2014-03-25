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
            ->add('email', 'email', array(
                'label' => 'Email',
                //'disabled' => true,
            ))
            /*->add('group', 'entity', array(
                'label' => 'Roles',
                'class' => 'Ekyna\Bundle\UserBundle\Entity\Group',
                'property' => 'name',
            ))*/
            /*->add('company', 'text', array(
                'label' => 'Entreprise',
                'required' => false
            ))
            ->add('firstname', 'text', array(
                'label' => 'Prénom',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nom',
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone'
            ))
            ->add('mobile', 'text', array(
                'label' => 'Mobile'
            ))*/
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
    	return 'ekyna_user';
    }
}
