<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

/**
 * Class ResettingType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ResettingType extends AbstractType
{
    private string $userClass;

    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                'invalid_message' => t('password.mismatch', [], 'EkynaUser'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'        => $this->userClass,
            'validation_groups' => ['Default', 'Resetting'],
        ]);
    }
}
