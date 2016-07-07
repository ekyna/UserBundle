<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

/**
 * Class AddressChoiceType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 * @deprecated
 */
class AddressChoiceType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;


    /**
     * Constructor.
     *
     * @param string $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'class'         => $this->dataClass,
                //'property_path' => 'id',
                'expanded'      => true,
                'user'          => null,
                'placeholder'   => false,
                'query_builder' => function (Options $options, $previousValue) {
                    if (null !== $user = $options['user']) {
                        return function (EntityRepository $er) use ($user) {
                            $qb = $er->createQueryBuilder('a');

                            return $qb
                                ->andWhere($qb->expr()->eq('a.user', ':user'))
                                //->andWhere($qb->expr()->eq('a.locked', ':locked'))
                                ->setParameter('user', $user)//->setParameter('locked', false)
                                ;
                        };
                    }

                    return $previousValue;
                },
            ])
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('user', ['null', 'Ekyna\Bundle\UserBundle\Model\UserInterface']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_user_address_choice';
    }
}
