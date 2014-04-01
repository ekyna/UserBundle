<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\AbstractTableType;

/**
 * GroupType
 */
class GroupType extends AbstractTableType
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
            ->addColumn('name', 'anchor', array(
                'label' => 'Nom',
                'sortable' => true,
                //'filterable' => true,
                'route_name' => 'ekyna_group_admin_show',
                'route_parameters_map' => array(
                    'groupId' => 'id'
                ),
            ))
            ->addColumn('actions', 'actions', array(
                'buttons' => array(
                    array(
                        'label' => 'Modifier',
                        'class' => 'warning',
                        'route_name' => 'ekyna_group_admin_edit',
                        'route_parameters_map' => array(
                            'groupId' => 'id'
                        ),
                    ),
                    array(
                        'label' => 'Supprimer',
                        'class' => 'danger',
                        'route_name' => 'ekyna_group_admin_remove',
                        'route_parameters_map' => array(
                            'groupId' => 'id'
                        ),
                    ),
                ),
            ))
            ->addFilter('id', 'number')
            ->addFilter('name', null, array(
            	'label' => 'Nom'
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
        return 'ekyna_group';
    }
}
