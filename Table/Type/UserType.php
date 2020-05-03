<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Bundle\ResourceBundle\Table\Filter\ResourceType;
use Ekyna\Bundle\TableBundle\Extension\Type as BType;
use Ekyna\Component\Table\Extension\Core\Type as CType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Table\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceTableType
{
    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('email', BType\Column\AnchorType::class, [
                'label'                => 'ekyna_user.user.label.singular',
                'route_name'           => 'ekyna_user_user_admin_show',
                'route_parameters_map' => ['userId' => 'id'],
                'position'             => 10,
            ])
            ->addColumn('username', CType\Column\TextType::class, [
                'label'    => 'ekyna_core.field.username',
                'position' => 20,
            ])
            ->addColumn('group', BType\Column\AnchorType::class, [
                'label'                => 'ekyna_core.field.group',
                'property_path'        => 'group.name',
                'route_name'           => 'ekyna_user_group_admin_show',
                'route_parameters_map' => ['groupId' => 'group.id'],
                'position'             => 30,
            ])
            ->addColumn('enabled', CType\Column\BooleanType::class, [
                'label'                => 'ekyna_core.field.enabled',
                'sortable'             => true,
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'enabled'],
                'route_parameters_map' => ['userId' => 'id'],
                'position'             => 40,
            ])
            /*->addColumn('locked', CType\Column\BooleanType::class, [
                'label'                => 'ekyna_core.field.locked',
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'locked'],
                'route_parameters_map' => ['userId' => 'id'],
                'position' => 50,
            ])
            ->addColumn('expired', CType\Column\BooleanType::class, [
                'label'                => 'ekyna_core.field.expired',
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route_name'           => 'ekyna_user_user_admin_toggle',
                'route_parameters'     => ['field' => 'expired'],
                'route_parameters_map' => ['userId' => 'id'],
                'position' => 60,
            ])
            ->addColumn('expiresAt', CType\Column\DateTimeType::class, [
                'label'    => 'ekyna_core.field.expires_at',
                'sortable' => true,
                'position' => 70,
            ])*/
            ->addColumn('createdAt', CType\Column\DateTimeType::class, [
                'label'    => 'ekyna_core.field.created_at',
                'sortable' => true,
                'position' => 80,
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
            ])
            ->addFilter('username', CType\Filter\TextType::class, [
                'label'    => 'ekyna_core.field.username',
                'position' => 20,
            ]);

        $builder
            ->addFilter('group', ResourceType::class, [
                'resource' => 'ekyna_user.group',
                'position' => 30,
            ])
            ->addFilter('enabled', CType\Filter\BooleanType::class, [
                'label'    => 'ekyna_core.field.enabled',
                'position' => 40,
            ])
            /*->addFilter('locked', CType\Filter\BooleanType::class, [
                'label' => 'ekyna_core.field.locked',
                'position' => 50,
            ])
            ->addFilter('expired', CType\Filter\BooleanType::class, [
                'label' => 'ekyna_core.field.expired',
                'position' => 60,
            ])
            ->addFilter('expiresAt', CType\Filter\DateTimeType::class, [
                'label' => 'ekyna_core.field.expires_at',
                'position' => 70,
            ])*/
            ->addFilter('createdAt', CType\Filter\DateTimeType::class, [
                'label'    => 'ekyna_core.field.created_at',
                'position' => 80,
            ]);
    }

    /**
     * @inheritDoc
     */
    /*public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

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
    }*/
}
