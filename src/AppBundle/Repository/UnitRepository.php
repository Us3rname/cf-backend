<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Unit;

/**
 * UniteRepository
 *
 */
class UnitRepository extends \Doctrine\ORM\EntityRepository
{

    public function createUnit($name, $abbreviation)
    {
        $unit = new Unit();
        $unit->setName($name);
        $unit->setAbbreviation($abbreviation);

        try {
            $this->getEntityManager()->persist($unit);
            $this->getEntityManager()->flush();
        } catch (\Exception $exception) {
            return $exception;
        }

        return $unit;
    }
}
