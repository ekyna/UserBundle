<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends ResourceTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $tableBuilder)
    {
        $tableBuilder
            ->addColumn('id', 'number', array(
                'sortable' => true,
            ))
            ->addColumn('email', 'anchor', array(
                'label' => 'ekyna_core.field.email',
                'sortable' => true,
                'route_name' => 'ekyna_user_user_admin_show',
                'route_parameters_map' => array(
                    'userId' => 'id'
                ),
            ))
            ->addColumn('group', 'anchor', array(
                'label' => 'ekyna_core.field.group',
                'property_path' => 'group.name',
                'sortable' => false,
                'route_name' => 'ekyna_user_group_admin_show',
                'route_parameters_map' => array(
                    'groupId' => 'group.id'
                ),
            ))
            ->addColumn('createdAt', 'datetime', array(
                'label' => 'ekyna_core.field.add_date',
                'sortable' => true,
            ))
            ->addColumn('actions', 'admin_actions', array(
                'buttons' => array(
                    array(
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_user_admin_edit',
                        'route_parameters_map' => array(
                            'userId' => 'id'
                        ),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_user_admin_remove',
                        'route_parameters_map' => array(
                            'userId' => 'id'
                        ),
                        'permission' => 'delete',
                    ),
                ),
            ))
            ->addFilter('id', 'number')
            ->addFilter('email', 'text', array(
            	'label' => 'ekyna_core.field.email'
            ))
            ->addFilter('createdAt', 'datetime', array(
                'label' => 'ekyna_core.field.add_date',
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
