<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 29/03/2018
 * Time: 18:34
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsDeceased
 *
 * @Annotation
 * @package App\Validator\Constraints
 */
class IsDeceased extends Constraint
{
    public $message = 'Vous ne pouvez pas sélectionner une cause de la mort si l\'oiseau n\'est pas mort.';
}