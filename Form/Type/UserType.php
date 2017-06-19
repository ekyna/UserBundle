<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceFormType
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var string
     */
    protected $groupClass;


    /**
     * Constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param string                $userClass
     * @param string                $groupClass
     */
    public function __construct(TokenStorageInterface $tokenStorage, $userClass, $groupClass)
    {
        parent::__construct($userClass);

        $this->tokenStorage = $tokenStorage;
        $this->groupClass = $groupClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $group */
        $group = $this->tokenStorage->getToken()->getUser()->getGroup();

        $builder
            ->add('group', EntityType::class, [
                'label'         => 'ekyna_core.field.group',
                'class'         => $this->groupClass,
                'choice_label'  => 'name',
                'query_builder' => function (EntityRepository $er) use ($group) {
                    $qb = $er->createQueryBuilder('g');

                    return $qb->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                },
            ])
            ->add('email', EmailType::class, [
                'label' => 'ekyna_core.field.email',
            ])
            ->add('username', TextType::class, [
                'label' => 'ekyna_core.field.username',
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
    }
}
