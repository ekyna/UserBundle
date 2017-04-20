<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\UserBundle\Event\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

use function Symfony\Component\Translation\t;

/**
 * Class RegistrationType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class RegistrationType extends AbstractType
{
    private EventDispatcherInterface $eventDispatcher;
    private string                   $userClass;
    private bool                     $check;

    public function __construct(EventDispatcherInterface $eventDispatcher, string $userClass, bool $check)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userClass = $userClass;
        $this->check = $check;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($this->check) {
            $builder->add('email', EmailType::class, [
                'label'    => t('field.email_address', [], 'EkynaUi'),
                'disabled' => true,
            ]);
        } else {
            $builder->add('email', RepeatedType::class, [
                'type'            => EmailType::class,
                'first_options'   => [
                    'label' => t('field.email_address', [], 'EkynaUi'),
                    'attr'  => [
                        'autocomplete' => 'email',
                    ],
                ],
                'second_options'  => [
                    'label' => t('field.verify', [], 'EkynaUi'),
                ],
                'invalid_message' => t('email.mismatch', [], 'EkynaUser'),
            ]);
        }

        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'options'         => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options'   => [
                    'label' => t('field.password', [], 'EkynaUi'),
                ],
                'second_options'  => [
                    'label' => t('field.verify', [], 'EkynaUi'),
                ],
                'invalid_message' => t('ekyna_user.password.mismatch', [], 'EkynaUser'),
            ]);

        $this->eventDispatcher->dispatch(new FormEvent($builder), FormEvent::FORM_REGISTRATION);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'        => $this->userClass,
            'validation_groups' => ['Default', 'Registration'],
        ]);
    }
}
