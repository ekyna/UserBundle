<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProfileType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ProfileType extends ProfileFormType
{
    /**
     * @var bool
     */
    private $usernameEnabled;

    /**
     * @param string $class
     * @param array $config
     */
    public function __construct($class, array $config)
    {
        parent::__construct($class);
        $this->usernameEnabled = $config['account']['username'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if (!$this->usernameEnabled) {
            $builder->remove('username');
        }

        $builder
            ->add('company', 'text', array(
                'label' => 'ekyna_core.field.company',
                'required' => false,
            ))
            ->add('identity', 'ekyna_user_identity')
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_profile';
    }
}
