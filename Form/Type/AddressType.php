<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AddressType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressType extends ResourceFormType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
        parent::setDefaultOptions($resolver);

        $resolver
            ->setDefaults(array(
                'company'           => true,
                'identity'          => true,
                'country'           => true,
                'phones'            => true,
                'company_required'  => false,
                'identity_required' => true,
                'phone_required'    => false,
                'mobile_required'   => false,
            ))
            ->addAllowedTypes(array(
                'company'           => 'bool',
                'identity'          => 'bool',
                'country'           => 'bool',
                'phones'            => 'bool',
                'company_required'  => 'bool',
                'identity_required' => 'bool',
                'phone_required'    => 'bool',
                'mobile_required'   => 'bool',
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'ekyna_address';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_user_address';
    }
}
