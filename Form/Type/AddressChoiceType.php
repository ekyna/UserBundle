<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

/**
 * Class AddressChoiceType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'class'    => $this->dataClass,
                'property' => 'id',
                'expanded' => true,
                'user'     => null,
                'empty_value' => false,
                'query_builder' => function(Options $options, $previousValue) {
                    if (null !== $user = $options['user']) {
                        return function(EntityRepository $er) use ($user) {
                            $qb = $er->createQueryBuilder('a');
                            return $qb
                                ->andWhere($qb->expr()->eq('a.user', ':user'))
                                //->andWhere($qb->expr()->eq('a.locked', ':locked'))
                                ->setParameter('user', $user)
                                //->setParameter('locked', false)
                            ;
                        };
                    }
                    return $previousValue;
                },
            ))
            ->setAllowedTypes(array(
                'class'    => 'string',
                'user'     => array('null', 'Ekyna\Bundle\UserBundle\Model\UserInterface'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_address_choice';
    }
}
