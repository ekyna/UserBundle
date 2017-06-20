<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\UserBundle\Form\EventListener\UserFormSubscriber;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RegistrationType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
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

            $builder->addEventSubscriber(new UserFormSubscriber());
        }
    }
}
