<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * AddressType
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
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
            ->add('street', 'text', array(
                'label' => 'ekyna_core.field.street',
            ))
            ->add('supplement', 'text', array(
                'label' => 'ekyna_core.field.supplement',
                'required' => false
            ))
            ->add('postalCode', 'text', array(
                'label' => 'ekyna_core.field.postal_code',
            ))
            ->add('city', 'text', array(
                'label' => 'ekyna_core.field.city',
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
    	return 'ekyna_user_address';
    }
}
