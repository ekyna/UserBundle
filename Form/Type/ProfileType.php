<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\Translation\t;

/**
 * Class ProfileType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ProfileType extends AbstractType
{
    private string $userClass;

    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => t('field.email_address', [], 'EkynaUi'),
            ])
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'        => $this->userClass,
            'validation_groups' => ['Default', 'Profile'],
        ]);
    }
}
