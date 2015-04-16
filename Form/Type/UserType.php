<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceFormType
{
    /**
     * @var string
     */
    protected $groupClass;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var bool
     */
    private $usernameEnabled;


    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param string $userClass
     * @param string $groupClass
     * @param array $config
     */
    public function __construct(SecurityContextInterface $securityContext, $userClass, $groupClass, array $config)
    {
        parent::__construct($userClass);

        $this->securityContext = $securityContext;
        $this->groupClass = $groupClass;
        $this->usernameEnabled = $config['account']['username'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $group */
        $group = $this->securityContext->getToken()->getUser()->getGroup();

        $builder
            ->add('group', 'entity', array(
                'label' => 'ekyna_core.field.group',
                'class' => $this->groupClass,
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($group) {
                    $qb = $er->createQueryBuilder('g');
                    return $qb->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                },
            ))
            ->add('email', 'email', array(
                'label' => 'ekyna_core.field.email',
            ))
            ->add('company', 'text', array(
                'label' => 'ekyna_core.field.company',
                'required' => false
            ))
            ->add('identity', 'ekyna_user_identity')
            ->add('phone', 'tel', array(
                'label' => 'ekyna_core.field.phone',
                'required' => false,
                'default_region' => 'FR', // TODO get user locale
                'format' => PhoneNumberFormat::NATIONAL,
            ))
            ->add('mobile', 'tel', array(
                'label' => 'ekyna_core.field.mobile',
                'required' => false,
                'default_region' => 'FR', // TODO get user locale
                'format' => PhoneNumberFormat::NATIONAL,
            ))
        ;

        if ($this->usernameEnabled) {
            $builder->add('username', 'text', array(
                'label' => 'ekyna_core.field.username',
            ));
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
                $user = $event->getData();

                if (null === $user || null === $user->getId()) {
                    $form = $event->getForm();
                    $form->add('sendCreationEmail', 'checkbox', array(
                        'label' => 'ekyna_user.user.field.send_creation_email',
                        'required' => false,
                        'attr' => array(
                            'align_with_widget' => true,
                        ),
                    ));
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_user_user';
    }
}
