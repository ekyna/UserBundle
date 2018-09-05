<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Ekyna\Bundle\UserBundle\Form\EventListener\UserFormSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceFormType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var string
     */
    protected $groupClass;


    /**
     * Constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param string                        $userClass
     * @param string                        $groupClass
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, $userClass, $groupClass)
    {
        parent::__construct($userClass);

        $this->authorizationChecker = $authorizationChecker;
        $this->groupClass = $groupClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group', EntityType::class, [
                'label'        => 'ekyna_core.field.group',
                'class'        => $this->groupClass,
                'choice_label' => 'name',
                'disabled'     => !$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN'),
            ])
            ->add('email', EmailType::class, [
                'label' => 'ekyna_core.field.email',
            ])
            ->add('username', TextType::class, [
                'label'    => 'ekyna_core.field.username',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label'    => 'ekyna_core.field.enabled',
                'required' => false,
                'attr'     => [
                    'align_with_widget' => true,
                ],
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
                $user = $event->getData();

                if (null === $user || null === $user->getId()) {
                    $form = $event->getForm();
                    $form->add('sendCreationEmail', CheckboxType::class, [
                        'label'    => 'ekyna_user.user.field.send_creation_email',
                        'required' => false,
                        'attr'     => [
                            'align_with_widget' => true,
                        ],
                    ]);
                }
            }
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
                $user = $event->getData();

                if (empty($user->getUsername())) {
                    $user->setUsername($user->getEmail());
                }
            }
        );

        $builder->addEventSubscriber(new UserFormSubscriber());
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('validation_groups', ['Default', 'Profile']);
    }
}
