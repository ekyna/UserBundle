<?php

namespace Ekyna\Bundle\UserBundle\Validator\Constraints;

use Ekyna\Bundle\UserBundle\Entity\Address as Entity;
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
        if (!$address instanceof Entity) {
            throw new UnexpectedTypeException($address, '\Ekyna\Bundle\UserBundle\Entity\Address');
        }
        if (!$constraint instanceof Address) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Address');
        }

        /**
         * @var Entity $address
         * @var Address $constraint
         */
        if (null === $address->getUser()) {
            if (0 === strlen($address->getFirstName())) {
                $this->context->addViolationAt('firstName', $constraint->message);
            }
            if (0 === strlen($address->getLastName())) {
                $this->context->addViolationAt('lastName', $constraint->message);
            }
        }
    }
}
