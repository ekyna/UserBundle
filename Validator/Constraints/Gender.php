<?php

namespace Ekyna\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Gender
 * @package Ekyna\Bundle\UserBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Gender extends Constraint
{
    public $invalidGender = 'ekyna_user.gender.invalid';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'ekyna_user_gender';
    }
}