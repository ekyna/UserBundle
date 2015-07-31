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
    public $userIsMandatory = 'ekyna_user.address.user_is_mandatory';
    public $genderIsMandatory = 'ekyna_user.address.gender_is_mandatory';
    public $firstNameIsMandatory = 'ekyna_user.address.first_name_is_mandatory';
    public $lastNameIsMandatory = 'ekyna_user.address.last_name_is_mandatory';
    public $invalidPostalCode = 'ekyna_user.address.invalid_postal_code';

    public $user = true;
    public $identity = true;

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
