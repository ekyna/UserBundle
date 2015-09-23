<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            $builder->add('company', 'text', [
                'label' => 'ekyna_core.field.company',
                'required' => $options['company_required'],
            ]);
        }
        if ($options['identity']) {
            $builder->add('identity', 'ekyna_user_identity', [
                'required' => $options['identity_required'],
            ]);
        }
        if ($options['phones']) {
            $builder
                ->add('phone', 'tel', [
                    'label' => 'ekyna_core.field.phone',
                    'required' => $options['phone_required'],
                    'default_region' => 'FR', // TODO get user locale
                    'format' => PhoneNumberFormat::NATIONAL,
                ])
                ->add('mobile', 'tel', [
                    'label' => 'ekyna_core.field.mobile',
                    'required' => $options['mobile_required'],
                    'default_region' => 'FR', // TODO get user locale
                    'format' => PhoneNumberFormat::NATIONAL,
                ])
            ;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'company'           => true,
                'identity'          => true,
                'country'           => true,
                'phones'            => true,
                'company_required'  => false,
                'identity_required' => true,
                'phone_required'    => false,
                'mobile_required'   => false,
            ])
            ->addAllowedTypes([
                'company'           => 'bool',
                'identity'          => 'bool',
                'country'           => 'bool',
                'phones'            => 'bool',
                'company_required'  => 'bool',
                'identity_required' => 'bool',
                'phone_required'    => 'bool',
                'mobile_required'   => 'bool',
            ])
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
