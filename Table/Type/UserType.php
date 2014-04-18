<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\AbstractTableType;

/**
 * UserType
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends AbstractTableType
{
    protected $entityClass;

    public function __construct($class)
    {
        $this->entityClass = $class;
    }

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
            ->addColumn('actions', 'actions', array(
                'buttons' => array(
                    array(
                        'label' => 'Modifier',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_user_admin_edit',
                        'route_parameters_map' => array(
                            'userId' => 'id'
                        ),
                    ),
                    array(
                        'label' => 'Supprimer',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_user_admin_remove',
                        'route_parameters_map' => array(
                            'userId' => 'id'
                        ),
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
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_user';
    }
}
