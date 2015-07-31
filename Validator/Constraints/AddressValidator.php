<?php

namespace Ekyna\Bundle\UserBundle\Validator\Constraints;

use Ekyna\Bundle\UserBundle\Model\AddressInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class AddressValidator
 * @package Ekyna\Bundle\UserBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($address, Constraint $constraint)
    {
        if (null === $address) {
            return;
        }

        if (!$address instanceof AddressInterface) {
            throw new UnexpectedTypeException($address, '\Ekyna\Bundle\UserBundle\Model\AddressInterface');
        }
        if (!$constraint instanceof Address) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Address');
        }

        /**
         * @var AddressInterface $address
         * @var Address $constraint
         */
        if (null === $address->getUser()) {
            if ($constraint->user) {
                $this->context->addViolationAt('user', $constraint->userIsMandatory);
            } elseif ($constraint->identity) {
                if (0 === strlen($address->getGender())) {
                    $this->context->addViolationAt('gender', $constraint->genderIsMandatory);
                }
                if (0 === strlen($address->getFirstName())) {
                    $this->context->addViolationAt('firstName', $constraint->firstNameIsMandatory);
                }
                if (0 === strlen($address->getLastName())) {
                    $this->context->addViolationAt('lastName', $constraint->lastNameIsMandatory);
                }
            }
        }
        if (0 === strlen($address->getCompany()) && $constraint->company) {
            $this->context->addViolationAt('company', $constraint->companyIsMandatory);
        }
        if (0 === strlen($address->getPhone()) && $constraint->phone) {
            $this->context->addViolationAt('phone', $constraint->phoneIsMandatory);
        }
        if (0 === strlen($address->getMobile()) && $constraint->mobile) {
            $this->context->addViolationAt('mobile', $constraint->mobileIsMandatory);
        }
    }
}
