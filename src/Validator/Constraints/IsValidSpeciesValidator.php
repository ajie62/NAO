<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 10/04/2018
 * Time: 14:28
 */

namespace App\Validator\Constraints;

use App\Entity\Species;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidSpeciesValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $data = $this->context->getRoot()->getData();
        $hasSpeciesID = !is_null($data->getSpecies());
        $species = null;

        if ($hasSpeciesID)
            $species = $this->em->getRepository(Species::class)->find($data->getSpecies());

        if (is_null($species))
            $this->context->buildViolation($constraint->message)->addViolation();
    }
}
