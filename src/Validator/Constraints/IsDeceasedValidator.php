<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 29/03/2018
 * Time: 18:36
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsDeceasedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $deceased = $this->context->getRoot()->getData()->getDeceased();
        $causeWithoutDeath = false === $deceased && !is_null($value);

        if ($causeWithoutDeath) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}