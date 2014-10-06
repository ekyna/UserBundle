<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Form\Type\AbstractAddressType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AddressType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressType extends AbstractAddressType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * Constructor.
     *
     * @param $class
     */
    public function __construct($class)
    {
        $this->dataClass = $class;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
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
