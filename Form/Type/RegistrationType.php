<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class RegistrationType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RegistrationType extends RegistrationFormType
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
            ->add('gender', 'ekyna_user_gender')
            ->add('firstName', 'text', array(
                'label' => 'ekyna_core.field.first_name',
            ))
            ->add('lastName', 'text', array(
                'label' => 'ekyna_core.field.last_name',
            ))
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
        return 'ekyna_user_registration';
    }
}
