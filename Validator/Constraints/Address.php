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
    public $userIsMandatory      = 'ekyna_user.address.user_is_mandatory';
    public $genderIsMandatory    = 'ekyna_user.address.gender_is_mandatory';
    public $firstNameIsMandatory = 'ekyna_user.address.first_name_is_mandatory';
    public $lastNameIsMandatory  = 'ekyna_user.address.last_name_is_mandatory';
    public $companyIsMandatory   = 'ekyna_user.address.company_is_mandatory';
    public $phoneIsMandatory     = 'ekyna_user.address.phone_is_mandatory';
    public $mobileIsMandatory    = 'ekyna_user.address.mobile_is_mandatory';

    public $user     = true;
    public $identity = true;
    public $company  = false;
    public $phone    = false;
    public $mobile   = false;
}
