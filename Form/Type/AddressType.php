<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * AddressType
 */
class AddressType extends AbstractType
{
    protected $dataClass;

    public function __construct($class)
    {
        $this->dataClass = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('company', 'text', array(
                'label' => 'Entreprise',
                'required' => false
            ))
            ->add('gender', 'ekyna_user_gender')
            ->add('firstname', 'text', array(
                'label' => 'Prénom',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nom',
            ))
            ->add('street', 'text', array(
                'label' => 'N° et rue',
            ))
            ->add('supplement', 'text', array(
                'label' => 'Complément',
                'required' => false
            ))
            ->add('postalCode', 'text', array(
                'label' => 'Code postal',
            ))
            ->add('city', 'text', array(
                'label' => 'Ville',
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
    	return 'ekyna_user_address';
    }
}
