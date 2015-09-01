<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceTableType
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var string
     */
    protected $groupClass;

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
     * Sets the groupClass.
     *
     * @param string $groupClass
     * @return UserType
     */
    public function setGroupClass($groupClass)
    {
        $this->groupClass = $groupClass;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $group */
        $group = $this->securityContext->getToken()->getUser()->getGroup();

        $builder
            ->addColumn('id', 'number', array(
                'sortable' => true,
            ))
            ->addColumn('email', 'anchor', array(
                'label' => 'ekyna_user.user.label.singular',
                'property_path' => null,
                'sortable' => true,
                'route_name' => 'ekyna_user_user_admin_show',
                'route_parameters_map' => array('userId' => 'id'),
            ))
            /*->addColumn('username', 'text', array(
                'label' => 'ekyna_core.field.username',
                'sortable' => true,
            ))*/
            /*->addColumn('firstName', 'text', array(
                'label' => 'ekyna_core.field.first_name',
                'sortable' => true,
            ))
            ->addColumn('lastName', 'text', array(
                'label' => 'ekyna_core.field.last_name',
                'sortable' => true,
            ))*/
            ->addColumn('group', 'anchor', array(
                'label' => 'ekyna_core.field.group',
                'property_path' => 'group.name',
                'sortable' => false,
                'route_name' => 'ekyna_user_group_admin_show',
                'route_parameters_map' => array('groupId' => 'group.id'),
            ))
            ->addColumn('enabled', 'boolean', array(
                'label' => 'ekyna_core.field.enabled',
                'sortable' => true,
                'route_name' => 'ekyna_user_user_admin_toggle',
                'route_parameters' => array('field' => 'enabled'),
                'route_parameters_map' => array('userId' => 'id'),
            ))
            ->addColumn('locked', 'boolean', array(
                'label' => 'ekyna_core.field.locked',
                'sortable' => true,
                'true_class'  => 'label-danger',
                'false_class' => 'label-success',
                'route_name' => 'ekyna_user_user_admin_toggle',
                'route_parameters' => array('field' => 'locked'),
                'route_parameters_map' => array('userId' => 'id'),
            ))
            ->addColumn('expired', 'boolean', array(
                'label' => 'ekyna_core.field.expired',
                'sortable' => true,
                'true_class'  => 'label-danger',
                'false_class' => 'label-success',
                'route_name' => 'ekyna_user_user_admin_toggle',
                'route_parameters' => array('field' => 'expired'),
                'route_parameters_map' => array('userId' => 'id'),
            ))
            ->addColumn('expiresAt', 'datetime', array(
                'label' => 'ekyna_core.field.expires_at',
                'sortable' => true,
            ))
            ->addColumn('createdAt', 'datetime', array(
                'label' => 'ekyna_core.field.created_at',
                'sortable' => true,
            ))
            ->addColumn('actions', 'admin_actions', array(
                'buttons' => array(
                    array(
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_user_admin_edit',
                        'route_parameters_map' => array('userId' => 'id'),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_user_admin_remove',
                        'route_parameters_map' => array('userId' => 'id'),
                        'permission' => 'delete',
                    ),
                ),
            ))
            ->addFilter('id', 'number')
            ->addFilter('email', 'text', array(
            	'label' => 'ekyna_core.field.email',
            ))
            /*->addFilter('username', 'text', array(
            	'label' => 'ekyna_core.field.username',
            ))*/
            ->addFilter('firstName', 'text', array(
            	'label' => 'ekyna_core.field.first_name',
            ))
            ->addFilter('lastName', 'text', array(
            	'label' => 'ekyna_core.field.last_name',
            ))
            ->addFilter('group', 'entity', array(
                'label' => 'ekyna_core.field.group',
                'class' => $this->groupClass,
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($group) {
                    $qb = $er->createQueryBuilder('g');
                    return $qb->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                },
            ))
            ->addFilter('enabled', 'boolean', array(
                'label' => 'ekyna_core.field.enabled',
            ))
            ->addFilter('locked', 'boolean', array(
                'label' => 'ekyna_core.field.locked',
            ))
            ->addFilter('expired', 'boolean', array(
                'label' => 'ekyna_core.field.expired',
            ))
            ->addFilter('expiresAt', 'datetime', array(
                'label' => 'ekyna_core.field.expires_at',
            ))
            ->addFilter('createdAt', 'datetime', array(
                'label' => 'ekyna_core.field.created_at',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        /** @var \Ekyna\Bundle\UserBundle\Model\GroupInterface $group */
        $group = $this->securityContext->getToken()->getUser()->getGroup();

        $resolver->setDefaults(array(
            'customize_qb' => function(QueryBuilder $qb, $alias) use ($group) {
                $qb
                    ->join($alias.'.group', 'g')
                    ->andWhere($qb->expr()->gte('g.position', $group->getPosition()))
                ;
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_user';
    }
}
