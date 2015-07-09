<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Form\Type\AbstractAddressType;
use libphonenumber\PhoneNumberFormat;
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

        if ($options['company']) {
            $builder->add('company', 'text', array(
                'label' => 'ekyna_core.field.company',
                'required' => $options['company_required'],
            ));
        }
        if ($options['identity']) {
            $builder->add('identity', 'ekyna_user_identity', array(
                'required' => $options['identity_required'],
            ));
        }
        if ($options['phones']) {
            $builder
                ->add('phone', 'tel', array(
                    'label' => 'ekyna_core.field.phone',
                    'required' => $options['phone_required'],
                    'default_region' => 'FR', // TODO get user locale
                    'format' => PhoneNumberFormat::NATIONAL,
                ))
                ->add('mobile', 'tel', array(
                    'label' => 'ekyna_core.field.mobile',
                    'required' => $options['mobile_required'],
                    'default_region' => 'FR', // TODO get user locale
                    'format' => PhoneNumberFormat::NATIONAL,
                ))
            ;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class'        => $this->dataClass,
                'company'           => true,
                'identity'          => true,
                'phones'            => true,
                'company_required'  => false,
                'identity_required' => true,
                'phone_required'    => true,
                'mobile_required'   => false,
            ))
            ->addAllowedTypes(array(
                'company'           => 'bool',
                'identity'          => 'bool',
                'phones'            => 'bool',
                'company_required'  => 'bool',
                'identity_required' => 'bool',
                'phone_required'    => 'bool',
                'mobile_required'   => 'bool',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_user_address';
    }
}
