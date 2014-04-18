<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\AbstractTableType;

/**
 * AddressType
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
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
                'label' => 'ekyna_core.field.street',
                'sortable' => true,
                'route_name' => 'ekyna_user_address_admin_show',
                'route_parameters_map' => array(
                    'userId' => 'id'
                ),
            ))
            ->addColumn('postalCode', 'text', array(
                'label' => 'ekyna_core.field.postal_code',
                'sortable' => true,
            ))
            ->addColumn('city', 'text', array(
                'label' => 'ekyna_core.field.city',
                'sortable' => true,
            ))
            ->addColumn('actions', 'actions', array(
                'buttons' => array(
                    array(
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_address_admin_edit',
                        'route_parameters_map' => array(
                            'addressId' => 'id'
                        ),
                    ),
                    array(
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_address_admin_remove',
                        'route_parameters_map' => array(
                            'addressId' => 'id'
                        ),
                    ),
                ),
            ))
            ->addFilter('id', 'number')
            ->addFilter('street', 'text', array(
            	'label' => 'ekyna_core.field.street'
            ))
            ->addFilter('postalCode', array(
            	'label' => 'ekyna_core.field.postal_code'
            ))
            ->addFilter('city', array(
            	'label' => 'ekyna_core.field.city'
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
        return 'ekyna_user_address';
    }
}
