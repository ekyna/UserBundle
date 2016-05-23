<?php

namespace Ekyna\Bundle\UserBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Form\Type\EntitySearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserSearchType
 * @package Ekyna\Bundle\UserBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UserSearchType extends AbstractType
{
    /**
     * @var string
     */
    private $userClass;


    /**
     * Constructor.
     *
     * @param string $userClass
     */
    public function __construct($userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label'        => 'ekyna_user.user.label.singular',
                'required'     => true,
                'entity'       => $this->userClass,
                'search_route' => 'ekyna_user_user_admin_search',
                'find_route'   => 'ekyna_user_user_admin_find',
                'allow_clear'  => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntitySearchType::class;
    }
}
