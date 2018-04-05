<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 02/04/2018
 * Time: 16:25
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class HasImageOrCapture
 * @Annotation
 * @package App\Validator\Constraints
 */
class HasImageOrCapture extends Constraint
{
    public $message = "Vous devez obligatoirement ajouter une photo.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}