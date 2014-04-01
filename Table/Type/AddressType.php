<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\AbstractTableType;

/**
 * AddressType
 */
class AddressType extends AbstractTableType
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
            ->addColumn('street', 'anchor', array(
                'label' => 'Rue',
                'sortable' => true,
                'filterable' => true,
                'route_name' => 'ekyna_address_admin_show',
                'route_parameters_map' => array(
                    'userId' => 'id'
                ),
            ))
            ->addColumn('postalCode', 'text', array(
                'sortable' => true,
            ))
            ->addColumn('city', 'text', array(
                'sortable' => true,
            ))
            ->addColumn('actions', 'actions', array(
                'buttons' => array(
                    array(
                        'label' => 'Modifier',
                        'class' => 'warning',
                        'route_name' => 'ekyna_address_admin_edit',
                        'route_parameters_map' => array(
                            'addressId' => 'id'
                        ),
                    ),
                    array(
                        'label' => 'Supprimer',
                        'class' => 'danger',
                        'route_name' => 'ekyna_address_admin_remove',
                        'route_parameters_map' => array(
                            'addressId' => 'id'
                        ),
                    ),
                ),
            ))
            ->addFilter('id', 'number')
            ->addFilter('street')
            ->addFilter('postalCode')
            ->addFilter('city')
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
        return 'ekyna_address';
    }
}
