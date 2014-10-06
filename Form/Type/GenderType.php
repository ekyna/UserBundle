<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class GenderType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GenderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'ekyna_core.field.gender',
            'expanded' => true,
            'choices' => array(
                'mr' => 'ekyna_core.gender.mr.long',
                'mrs' => 'ekyna_core.gender.mrs.long',
                'miss' => 'ekyna_core.gender.miss.long',
            ),
            'attr' => array(
            	'class' => 'inline'
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_user_gender';
    }
}
