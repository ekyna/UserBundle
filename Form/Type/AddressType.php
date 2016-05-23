<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AddressType extends ResourceFormType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['company']) {
            $builder->add('company', TextType::class, [
                'label'    => 'ekyna_core.field.company',
                'required' => $options['company_required'],
            ]);
        }
        if ($options['identity']) {
            $builder->add('identity', IdentityType::class, [
                'required' => $options['identity_required'],
            ]);
        }
        if ($options['phones']) {
            $builder
                ->add('phone', PhoneNumberType::class, [
                    'label'          => 'ekyna_core.field.phone',
                    'required'       => $options['phone_required'],
                    'default_region' => 'FR', // TODO get user locale
                    'format'         => PhoneNumberFormat::NATIONAL,
                ])
                ->add('mobile', PhoneNumberType::class, [
                    'label'          => 'ekyna_core.field.mobile',
                    'required'       => $options['mobile_required'],
                    'default_region' => 'FR', // TODO get user locale
                    'format'         => PhoneNumberFormat::NATIONAL,
                ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'company'           => true,
            'identity'          => true,
            'country'           => true,
            'phones'            => true,
            'company_required'  => false,
            'identity_required' => true,
            'phone_required'    => false,
            'mobile_required'   => false,
        ]);

        $resolver->setAllowedTypes('company', 'bool');
        $resolver->setAllowedTypes('identity', 'bool');
        $resolver->setAllowedTypes('country', 'bool');
        $resolver->setAllowedTypes('phones', 'bool');
        $resolver->setAllowedTypes('company_required', 'bool');
        $resolver->setAllowedTypes('identity_required', 'bool');
        $resolver->setAllowedTypes('phone_required', 'bool');
        $resolver->setAllowedTypes('mobile_required', 'bool');
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return \Ekyna\Bundle\CoreBundle\Form\Type\AddressType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_user_address';
    }
}
