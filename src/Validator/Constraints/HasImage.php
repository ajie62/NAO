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
 * Class HasImage
 * @Annotation
 * @package App\Validator\Constraints
 */
class HasImage extends Constraint
{
    public $message = "Vous devez obligatoirement ajouter une photo.";

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}