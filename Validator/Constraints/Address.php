<?php

namespace Ekyna\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Address
 * @package Ekyna\Bundle\UserBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Address extends Constraint
{
    public $message = 'This value should not be blank.';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}