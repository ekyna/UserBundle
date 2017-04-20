<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\Translation\t;

/**
 * Class ChangePasswordType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ChangePasswordType extends AbstractType
{
    private string $userClass;

    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label'       => t('account.change_password.current', [], 'EkynaUser'),
                'mapped'      => false,
                'constraints' => [
                    new NotBlank(),
                    new UserPassword([
                        'message' => 'ekyna_user.current_password.invalid',
                    ]),
                ],
                'attr'        => [
                    'autocomplete' => 'current-password',
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'options'         => [
                    'attr'               => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options'   => [
                    'label' => t('account.change_password.new', [], 'EkynaUser'),
                ],
                'second_options'  => [
                    'label' => t('account.change_password.confirm', [], 'EkynaUser'),
                ],
                'invalid_message' => t('password.mismatch', [], 'EkynaUser'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => $this->userClass,
            'validation_groups' => ['Default', 'ChangePassword'],
        ]);
    }
}
