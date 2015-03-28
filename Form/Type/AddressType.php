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

        if ($options['identity']) {
            $builder->add('identity', 'ekyna_user_identity', array(
                'required' => false,
            ));
        }
        if ($options['company']) {
            $builder->add('company', 'text', array(
                'label' => 'ekyna_core.field.company',
                'required' => false,
            ));
        }
        if ($options['phones']) {
            $builder
                ->add('phone', 'tel', array(
                    'label' => 'ekyna_core.field.phone',
                    'required' => false,
                    'default_region' => 'FR', // TODO get user locale
                    'format' => PhoneNumberFormat::NATIONAL,
                ))
                ->add('mobile', 'tel', array(
                    'label' => 'ekyna_core.field.mobile',
                    'required' => false,
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
                'data_class' => $this->dataClass,
                'company'    => true,
                'identity'   => true,
                'phones'     => true,
            ))
            ->addAllowedTypes(array(
                'company'    => 'bool',
                'identity'   => 'bool',
                'phones'     => 'bool',
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
