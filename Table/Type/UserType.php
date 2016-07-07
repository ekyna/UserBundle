<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Table\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceTableType
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
     * Sets the security token storage.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Sets the groupClass.
     *
     * @param string $groupClass
     */
    public function setGroupClass($groupClass)
    {
        $this->groupClass = $groupClass;
    }

    /**
     * Returns the current user's group.
     *
     * @return \Ekyna\Bundle\UserBundle\Model\GroupInterface|null
     */
    private function getUserGroup()
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
            if (null !== $user = $token->getUser()) {
                return $user->getGroup();
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $group = $this->getUserGroup();

        $builder
            ->addColumn('id', 'number', [
                'sortable' => true,
            ])
            ->addColumn('email', 'anchor', [
                'label'                => 'ekyna_user.user.label.singular',
                'property_path'        => null,
                'sortable'             => true,
                'route_name'           => 'ekyna_user_user_admin_show',
                'route_parameters_map' => ['userId' => 'id'],
            ])
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
        ;
        if (null !== $group) {
            $builder
                ->addColumn('group', 'anchor', [
                    'label'                => 'ekyna_core.field.group',
                    'property_path'        => 'group.name',
                    'sortable'             => false,
                    'route_name'           => 'ekyna_user_group_admin_show',
                    'route_parameters_map' => ['groupId' => 'group.id'],
                ]);
        }
        $builder
            ->addColumn('enabled', 'boolean', [
                'label'                => 'ekyna_core.field.enabled',
                'sortable'             => true,
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'enabled'],
                'route_parameters_map' => ['userId' => 'id'],
            ])
            ->addColumn('locked', 'boolean', [
                'label'                => 'ekyna_core.field.locked',
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'locked'],
                'route_parameters_map' => ['userId' => 'id'],
            ])
            ->addColumn('expired', 'boolean', [
                'label'                => 'ekyna_core.field.expired',
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'expired'],
                'route_parameters_map' => ['userId' => 'id'],
            ])
            ->addColumn('expiresAt', 'datetime', [
                'label'    => 'ekyna_core.field.expires_at',
                'sortable' => true,
            ])
            ->addColumn('createdAt', 'datetime', [
                'label'    => 'ekyna_core.field.created_at',
                'sortable' => true,
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label'                => 'ekyna_core.button.edit',
                        'class'                => 'warning',
                        'route_name'           => 'ekyna_user_user_admin_edit',
                        'route_parameters_map' => ['userId' => 'id'],
                        'permission'           => 'edit',
                    ],
                    [
                        'label'                => 'ekyna_core.button.remove',
                        'class'                => 'danger',
                        'route_name'           => 'ekyna_user_user_admin_remove',
                        'route_parameters_map' => ['userId' => 'id'],
                        'permission'           => 'delete',
                    ],
                ],
            ])
            ->addFilter('id', 'number')
            ->addFilter('email', 'text', [
                'label' => 'ekyna_core.field.email',
            ])
            /*->addFilter('username', 'text', array(
                'label' => 'ekyna_core.field.username',
            ))*/
            ->addFilter('firstName', 'text', [
                'label' => 'ekyna_core.field.first_name',
            ])
            ->addFilter('lastName', 'text', [
                'label' => 'ekyna_core.field.last_name',
            ]);
        if (null !== $group) {
            $builder
                ->addFilter('group', 'entity', [
                    'label'         => 'ekyna_core.field.group',
                    'class'         => $this->groupClass,
                    'property'      => 'name',
                    'query_builder' => function (EntityRepository $er) use ($group) {
                        $qb = $er->createQueryBuilder('g');

                        return $qb->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                    },
                ]);
        }
        $builder
            ->addFilter('enabled', 'boolean', [
                'label' => 'ekyna_core.field.enabled',
            ])
            ->addFilter('locked', 'boolean', [
                'label' => 'ekyna_core.field.locked',
            ])
            ->addFilter('expired', 'boolean', [
                'label' => 'ekyna_core.field.expired',
            ])
            ->addFilter('expiresAt', 'datetime', [
                'label' => 'ekyna_core.field.expires_at',
            ])
            ->addFilter('createdAt', 'datetime', [
                'label' => 'ekyna_core.field.created_at',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        if (null !== $group = $this->getUserGroup()) {
            $resolver->setDefaults([
                'customize_qb' => function (QueryBuilder $qb, $alias) use ($group) {
                    $qb
                        ->join($alias . '.group', 'g')
                        ->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                },
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_user';
    }
}
