<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * GenderType
 *
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
                'mr' => 'ekyna_core.gender.mr',
                'mrs' => 'ekyna_core.gender.mrs',
                'miss' => 'ekyna_core.gender.miss',
            ),
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
