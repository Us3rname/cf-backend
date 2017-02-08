<?php

namespace AppBundle\Repository;

use AppBundle\Entity\CrossfitUser;
use AppBundle\Entity\Exercise;
use AppBundle\Entity\ExerciseResult;
use AppBundle\Entity\Unit;

/**
 * UniteRepository
 *
 */
class ExerciseResultRepository extends \Doctrine\ORM\EntityRepository
{

    public function logResult(float $result, CrossfitUser $user, Exercise $exercise, Unit $unit)
    {
        $exerciseResult = new ExerciseResult($user, $unit, $exercise, $result);

        try {
            $this->getEntityManager()->persist($exerciseResult);
            $this->getEntityManager()->flush();
        } catch (\Exception $exception) {
            return $exception;
        }

        return $unit;
    }
}
