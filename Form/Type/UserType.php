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
                'label' => 'Groupe',
                'class' => 'Ekyna\Bundle\UserBundle\Entity\Group',
                'property' => 'name',
            ))
            ->add('username', 'text', array(
                'label' => 'Pseudo',
                'required' => false,
            ))
            ->add('email', 'email', array(
                'label' => 'Email',
                //'disabled' => true,
            ))
            ->add('company', 'text', array(
                'label' => 'Entreprise',
                'required' => false
            ))
            ->add('gender', 'ekyna_gender')
            ->add('firstname', 'text', array(
                'label' => 'Prénom',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nom',
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone',
                'required' => false
            ))
            ->add('mobile', 'text', array(
                'label' => 'Mobile',
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
    	return 'ekyna_user';
    }
}
