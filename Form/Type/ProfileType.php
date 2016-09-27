<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProfileType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ProfileType extends ProfileFormType
{
    /**
     * @var bool
     */
    private $usernameEnabled;


    /**
     * @param string $class
     * @param array  $config
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
    }
}
