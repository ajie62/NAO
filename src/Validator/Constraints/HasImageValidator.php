<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 02/04/2018
 * Time: 16:27
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HasImageValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $image = $this->context->getRoot()->getData()->getImage();

        if (is_null($image)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}