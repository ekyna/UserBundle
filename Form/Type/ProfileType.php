<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProfileType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ProfileType extends ProfileFormType
{
    private $usernameEnabled;

    /**
     * @param string $class The User class name
     * @param bool $usernameEnabled Whether to add the username field or not
     */
    public function __construct($class, $usernameEnabled = true)
    {
        parent::__construct($class);
        $this->usernameEnabled = $usernameEnabled;
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
                'required' => false
            ))
            ->add('gender', 'ekyna_user_gender')
            ->add('firstName', 'text', array(
                'label' => 'ekyna_core.field.first_name',
            ))
            ->add('lastName', 'text', array(
                'label' => 'ekyna_core.field.last_name',
            ))
            ->add('phone', 'text', array(
                'label' => 'ekyna_core.field.phone',
                'required' => false
            ))
            ->add('mobile', 'text', array(
                'label' => 'ekyna_core.field.mobile',
                'required' => false
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
