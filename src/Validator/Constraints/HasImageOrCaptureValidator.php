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

class HasImageOrCaptureValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $image = $this->context->getRoot()->getData()->getImage();
        $capture = $this->context->getRoot()->getData()->getCapture()->getFile();

        if (is_null($image) && is_null($capture)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}