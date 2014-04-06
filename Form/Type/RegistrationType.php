<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * ProfileType
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RegistrationType extends RegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('company', 'text', array(
                'label' => 'Entreprise',
                'required' => false
            ))
            ->add('gender', 'ekyna_gender')
            ->add('firstname', 'text', array(
                'label' => 'Prénom',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nom',
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone',
                'required' => false
            ))
            ->add('mobile', 'text', array(
                'label' => 'Mobile',
                'required' => false
            ))
        ;
    }

    public function getName()
    {
        return 'ekyna_user_registration';
    }
}
