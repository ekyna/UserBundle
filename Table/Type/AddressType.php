<?php

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class AddressType
 * @package Ekyna\Bundle\UserBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressType extends ResourceTableType
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
            ->addColumn('street', 'anchor', [
                'label' => 'ekyna_core.field.street',
                'sortable' => true,
                'route_name' => 'ekyna_user_address_admin_show',
                'route_parameters_map' => [
                    'userId' => 'id'
                ],
            ])
            ->addColumn('postalCode', 'text', [
                'label' => 'ekyna_core.field.postal_code',
                'sortable' => true,
            ])
            ->addColumn('city', 'text', [
                'label' => 'ekyna_core.field.city',
                'sortable' => true,
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_user_address_admin_edit',
                        'route_parameters_map' => [
                            'addressId' => 'id'
                        ],
                        'permission' => 'edit',
                    ],
                    [
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_user_address_admin_remove',
                        'route_parameters_map' => [
                            'addressId' => 'id'
                        ],
                        'permission' => 'delete',
                    ],
                ],
            ])
            ->addFilter('id', 'number')
            ->addFilter('street', 'text', [
            	'label' => 'ekyna_core.field.street'
            ])
            ->addFilter('postalCode', 'text', [
            	'label' => 'ekyna_core.field.postal_code'
            ])
            ->addFilter('city', 'text', [
            	'label' => 'ekyna_core.field.city'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_address';
    }
}
