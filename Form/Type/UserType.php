<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceFormType
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Sets the securityContext.
     *
     * @param SecurityContextInterface $securityContext
     * @return UserType
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
        return $this;
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
                'class' => 'Ekyna\Bundle\UserBundle\Entity\Group',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($group) {
                    $qb = $er->createQueryBuilder('g');
                    return $qb->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                },
            ))
            /*->add('username', 'text', array(
                'label' => 'ekyna_core.field.username',
                'required' => false,
            ))*/
            ->add('email', 'email', array(
                'label' => 'ekyna_core.field.email',
                //'disabled' => true,
            ))
            ->add('company', 'text', array(
                'label' => 'ekyna_core.field.company',
                'required' => false
            ))
            ->add('gender', 'ekyna_user_gender', array('expanded' => false))
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
    	return 'ekyna_user_user';
    }
}
