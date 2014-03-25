<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\AbstractTableType;

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
                'label' => 'Email',
                'sortable' => true,
                'filterable' => true,
                'route_name' => 'ekyna_user_admin_show',
                'route_parameters_map' => array(
                    'userId' => 'id'
                ),
            ))
            ->addColumn('group', 'anchor', array(
                'label' => 'Groupe',
                'property_path' => 'group.name',
                'sortable' => true,
                'filterable' => true,
                'route_name' => 'ekyna_group_admin_show',
                'route_parameters_map' => array(
                    'groupId' => 'group.id'
                ),
            ))
            ->addColumn('createdAt', 'datetime', array(
                'sortable' => true,
                'label' => 'Date de création',
            ))
            ->addColumn('actions', 'actions', array(
                'buttons' => array(
                    array(
                        'label' => 'Modifier',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_admin_edit',
                        'route_parameters_map' => array(
                            'userId' => 'id'
                        ),
                    ),
                    array(
                        'label' => 'Supprimer',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_admin_remove',
                        'route_parameters_map' => array(
                            'userId' => 'id'
                        ),
                    ),
                ),
            ))
            ->addFilter('id', 'number')
            ->addFilter('email')
            ->addFilter('createdAt', 'datetime', array(
                'label' => 'Date de création',
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
        return 'ekyna_user';
    }
}