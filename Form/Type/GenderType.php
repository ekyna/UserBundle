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
            'label' => 'Civilité',
            'expanded' => true,
            'choices' => array(
                'Mr' => 'Monsieur',
                'Mme' => 'Madame',
                'Melle' => 'Mademoiselle',
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
