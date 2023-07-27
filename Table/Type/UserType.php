<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Table\Type;

use Ekyna\Bundle\ResourceBundle\Table\Type\AbstractResourceType;
use Ekyna\Bundle\TableBundle\Extension\Type as BType;
use Ekyna\Component\Table\Extension\Core\Type as CType;
use Ekyna\Component\Table\TableBuilderInterface;

use function Symfony\Component\Translation\t;

/**
 * Class UserType
 * @package Ekyna\Bundle\UserBundle\Table\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserType extends AbstractResourceType
{
    public function buildTable(TableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addColumn('email', BType\Column\AnchorType::class, [
                'label'    => t('field.email', [], 'EkynaUi'),
                'position' => 10,
            ])
            ->addColumn('enabled', CType\Column\BooleanType::class, [
                'label'    => t('field.enabled', [], 'EkynaUi'),
                'position' => 40,
            ])
            /*->addColumn('locked', CType\Column\BooleanType::class, [
                'label'                => t('field.locked', [], 'EkynaUi'),
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route'           => 'ekyna_user_user_admin_toggle',
                'parameters'     => ['field' => 'locked'],
                'parameters_map' => ['userId' => 'id'],
                'position' => 50,
            ])
            ->addColumn('expired', CType\Column\BooleanType::class, [
                'label'                => t('field.expired', [], 'EkynaUi'),
                'sortable'             => true,
                'true_class'           => 'label-danger',
                'false_class'          => 'label-success',
                'route'           => 'ekyna_user_user_admin_toggle',
                'parameters'     => ['field' => 'expired'],
                'parameters_map' => ['userId' => 'id'],
                'position' => 60,
            ])
            ->addColumn('expiresAt', CType\Column\DateTimeType::class, [
                'label'    => t('field.expires_at', [], 'EkynaUi'),
                'sortable' => true,
                'position' => 70,
            ])*/
            ->addColumn('createdAt', CType\Column\DateTimeType::class, [
                'label'    => t('field.created_at', [], 'EkynaUi'),
                'position' => 80,
            ])
            ->addColumn('actions', BType\Column\ActionsType::class, [
                'resource' => $this->dataClass,
            ])
            ->addFilter('email', CType\Filter\TextType::class, [
                'label'    => t('field.email', [], 'EkynaUi'),
                'position' => 10,
            ])
            ->addFilter('enabled', CType\Filter\BooleanType::class, [
                'label'    => t('field.enabled', [], 'EkynaUi'),
                'position' => 40,
            ])
            /*->addFilter('locked', CType\Filter\BooleanType::class, [
                'label' => t('field.locked', [], 'EkynaUi'),
                'position' => 50,
            ])
            ->addFilter('expired', CType\Filter\BooleanType::class, [
                'label' => t('field.expired', [], 'EkynaUi'),
                'position' => 60,
            ])
            ->addFilter('expiresAt', CType\Filter\DateTimeType::class, [
                'label' => 'ekyna_core.field.expires_at',
                'position' => 70,
            ])*/
            ->addFilter('createdAt', CType\Filter\DateTimeType::class, [
                'label'    => t('field.created_at', [], 'EkynaUi'),
                'position' => 80,
            ]);
    }
}
