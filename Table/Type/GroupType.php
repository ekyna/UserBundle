<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class GroupType
 * @package Ekyna\Bundle\UserBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GroupType extends ResourceTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('id', 'number', [
                'sortable' => true,
            ])
            ->addColumn('name', 'anchor', [
                'label' => 'ekyna_core.field.name',
                'sortable' => true,
                'route_name' => 'ekyna_user_group_admin_show',
                'route_parameters_map' => [
                    'groupId' => 'id'
                ],
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label' => 'ekyna_user.group.button.edit_permissions',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_group_admin_edit_permissions',
                        'route_parameters_map' => [
                            'groupId' => 'id'
                        ],
                        'permission' => 'edit',
                    ],
                    [
                        'label' => 'ekyna_core.button.move_up',
                        'icon' => 'arrow-up',
                        'class' => 'primary',
                        'route_name' => 'ekyna_user_group_admin_move_up',
                        'route_parameters_map' => [
                            'groupId' => 'id'
                        ],
                        'permission' => 'edit',
                    ],
                    [
                        'label' => 'ekyna_core.button.move_down',
                        'icon' => 'arrow-down',
                        'class' => 'primary',
                        'route_name' => 'ekyna_user_group_admin_move_down',
                        'route_parameters_map' => [
                            'groupId' => 'id'
                        ],
                        'permission' => 'edit',
                    ],
                    [
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_group_admin_edit',
                        'route_parameters_map' => [
                            'groupId' => 'id'
                        ],
                        'permission' => 'edit',
                    ],
                    [
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_group_admin_remove',
                        'route_parameters_map' => [
                            'groupId' => 'id'
                        ],
                        'permission' => 'delete',
                    ],
                ],
            ])
            ->addFilter('id', 'number')
            ->addFilter('name', 'text', [
            	'label' => 'ekyna_core.field.name'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_group';
    }
}
