<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 10/04/2018
 * Time: 14:26
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsValidSpecies
 * @Annotation
 * @package App\Validator\Constraints
 */
class IsValidSpecies extends Constraint
{
    public $message = "Veuillez entrer un nom d'oiseau valide.";
}