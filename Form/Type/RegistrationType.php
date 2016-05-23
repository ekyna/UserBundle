<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
     * @var string
     */
    private $kernelEnvironment;


    /**
     * Constructor.
     *
     * @param string $class
     * @param array  $config
     * @param string $environment
     */
    public function __construct($class, array $config, $environment)
    {
        parent::__construct($class);

        $this->usernameEnabled = $config['account']['username'];
        $this->kernelEnvironment = $environment;
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
            ->add('company', TextType::class, [
                'label' => 'ekyna_core.field.company',
                'required' => false,
            ])
            ->add('identity', IdentityType::class)
            ->add('phone', PhoneNumberType::class, [
                'label' => 'ekyna_core.field.phone',
                'required' => false,
                'default_region' => 'FR', // TODO get user locale
                'format' => PhoneNumberFormat::NATIONAL,
            ])
            ->add('mobile', PhoneNumberType::class, [
                'label' => 'ekyna_core.field.mobile',
                'required' => false,
                'default_region' => 'FR', // TODO get user locale
                'format' => PhoneNumberFormat::NATIONAL,
            ])
        ;

        if ($this->kernelEnvironment !== 'test') {
            $builder->add('captcha', 'ekyna_captcha');
        }
    }
}
