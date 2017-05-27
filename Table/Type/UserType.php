<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Bundle\TableBundle\Extension\Type as BType;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Source\EntitySource;
use Ekyna\Component\Table\Bridge\Doctrine\ORM\Type\Filter\EntityType;
use Ekyna\Component\Table\Extension\Core\Type as CType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
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

        $builder->addColumn('email', BType\Column\AnchorType::class, [
            'label'                => 'ekyna_user.user.label.singular',
            'property_path'        => null,
            'sortable'             => true,
            'route_name'           => 'ekyna_user_user_admin_show',
            'route_parameters_map' => ['userId' => 'id'],
            'position'             => 10,
        ]);

        if (null !== $group) {
            $builder->addColumn('group', BType\Column\AnchorType::class, [
                'label'                => 'ekyna_core.field.group',
                'property_path'        => 'group.name',
                'sortable'             => false,
                'route_name'           => 'ekyna_user_group_admin_show',
                'route_parameters_map' => ['groupId' => 'group.id'],
                'position'             => 20,
            ]);
        }

        $builder
            ->addColumn('enabled', CType\Column\BooleanType::class, [
                'label'                => 'ekyna_core.field.enabled',
                'sortable'             => true,
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'enabled'],
                'route_parameters_map' => ['userId' => 'id'],
                'position'             => 30,
            ])
            /*->addColumn('locked', CType\Column\BooleanType::class, [
                'label'                => 'ekyna_core.field.locked',
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'locked'],
                'route_parameters_map' => ['userId' => 'id'],
                'position' => 40,
            ])
            ->addColumn('expired', CType\Column\BooleanType::class, [
                'label'                => 'ekyna_core.field.expired',
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'expired'],
                'route_parameters_map' => ['userId' => 'id'],
                'position' => 50,
            ])
            ->addColumn('expiresAt', CType\Column\DateTimeType::class, [
                'label'    => 'ekyna_core.field.expires_at',
                'sortable' => true,
                'position' => 60,
            ])*/
            ->addColumn('createdAt', CType\Column\DateTimeType::class, [
                'label'    => 'ekyna_core.field.created_at',
                'sortable' => true,
                'position' => 70,
            ])
            ->addColumn('actions', BType\Column\ActionsType::class, [
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
            ->addFilter('email', CType\Filter\TextType::class, [
                'label'    => 'ekyna_core.field.email',
                'position' => 10,
            ]);

        if (null !== $group) {
            $builder
                ->addFilter('group', EntityType::class, [
                    'label'         => 'ekyna_core.field.group',
                    'class'         => $this->groupClass,
                    'entity_label'  => 'name',
                    'query_builder' => function (EntityRepository $er) use ($group) {
                        $qb = $er->createQueryBuilder('g');

                        return $qb->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                    },
                    'position'      => 20,
                ]);
        }

        $builder
            ->addFilter('enabled', CType\Filter\BooleanType::class, [
                'label'    => 'ekyna_core.field.enabled',
                'position' => 30,
            ])
            /*->addFilter('locked', CType\Filter\BooleanType::class, [
                'label' => 'ekyna_core.field.locked',
                'position' => 40,
            ])
            ->addFilter('expired', CType\Filter\BooleanType::class, [
                'label' => 'ekyna_core.field.expired',
                'position' => 50,
            ])
            ->addFilter('expiresAt', CType\Filter\DateTimeType::class, [
                'label' => 'ekyna_core.field.expires_at',
                'position' => 60,
            ])*/
            ->addFilter('createdAt', CType\Filter\DateTimeType::class, [
                'label'    => 'ekyna_core.field.created_at',
                'position' => 70,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        /** @noinspection PhpUnusedParameterInspection */
        $resolver->setNormalizer('source', function (Options $options, $value) {
            if (null !== $group = $this->getUserGroup()) {
                if ($value instanceof EntitySource) {
                    $value->setQueryBuilderInitializer(function (QueryBuilder $qb, $alias) use ($group) {
                        $qb
                            ->join($alias . '.group', 'g')
                            ->andWhere($qb->expr()->gte('g.position', $group->getPosition()));
                    });
                }
            }

            return $value;
        });
    }
}
