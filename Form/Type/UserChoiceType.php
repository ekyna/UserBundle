<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

/**
 * Class UserChoiceType
 * @package    Ekyna\Bundle\UserBundle\Form\Type
 * @author     Etienne Dauvergne <contact@ekyna.com>
 *
 * @deprecated User resource choice type (?)
 * @TODO Remove
 */
class UserChoiceType extends AbstractType
{
    private string $userClass;

    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label'         => t('user.label.singular', [], 'EkynaUser'),
                'placeholder'   => 'value.choose',
                'class'         => $this->userClass,
                'roles'         => [],
                'select2'       => false,
                'query_builder' => function (Options $options, $value) {
                    if (null !== $value) {
                        return $value;
                    }

                    return function (EntityRepository $repository) use ($options) {
                        $qb = $repository
                            ->createQueryBuilder('u')
                            ->join('u.group', 'g')
                            ->orderBy('u.username', 'ASC');

                        $expr = $qb->expr();

                        $roles = $options['roles'];
                        if (1 == count($roles)) {
                            $qb->andWhere($expr->like('g.roles', $expr->literal('%"' . $roles[0] . '"%')));
                        } elseif (!empty($roles)) {
                            $orRoles = $expr->orX();
                            foreach ($roles as $role) {
                                $orRoles->add($expr->like('g.roles', $expr->literal('%"' . $role . '"%')));
                            }
                            $qb->andWhere($orRoles);
                        }

                        return $qb;
                    };
                },
            ])
            ->setAllowedTypes('roles', 'array');
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
