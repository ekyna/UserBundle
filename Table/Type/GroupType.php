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
            ->addColumn('id', 'number', array(
                'sortable' => true,
            ))
            ->addColumn('name', 'anchor', array(
                'label' => 'ekyna_core.field.name',
                'sortable' => true,
                'route_name' => 'ekyna_user_group_admin_show',
                'route_parameters_map' => array(
                    'groupId' => 'id'
                ),
            ))
            ->addColumn('actions', 'admin_actions', array(
                'buttons' => array(
                    array(
                        'label' => 'ekyna_user.group.button.edit_permissions',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_group_admin_edit_permissions',
                        'route_parameters_map' => array(
                            'groupId' => 'id'
                        ),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.move_up',
                        'icon' => 'arrow-up',
                        'class' => 'primary',
                        'route_name' => 'ekyna_user_group_admin_move_up',
                        'route_parameters_map' => array(
                            'groupId' => 'id'
                        ),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.move_down',
                        'icon' => 'arrow-down',
                        'class' => 'primary',
                        'route_name' => 'ekyna_user_group_admin_move_down',
                        'route_parameters_map' => array(
                            'groupId' => 'id'
                        ),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_group_admin_edit',
                        'route_parameters_map' => array(
                            'groupId' => 'id'
                        ),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_group_admin_remove',
                        'route_parameters_map' => array(
                            'groupId' => 'id'
                        ),
                        'permission' => 'delete',
                    ),
                ),
            ))
            ->addFilter('id', 'number')
            ->addFilter('name', 'text', array(
            	'label' => 'ekyna_core.field.name'
            ))
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
