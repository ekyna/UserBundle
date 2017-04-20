<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\ResourceBundle\Form\Type\ResourceSearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserSearchType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserSearchType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'resource' => 'ekyna_user.user',
                'required' => true,
                'roles'    => [],
            ])
            ->setAllowedTypes('roles', 'array')
            ->setNormalizer('search_parameters', function (Options $options, $value) {
                if (!isset($value['roles'])) {
                    $value['roles'] = $options['roles'];
                }

                return $value;
            });
    }

    public function getParent(): ?string
    {
        return ResourceSearchType::class;
    }
}
