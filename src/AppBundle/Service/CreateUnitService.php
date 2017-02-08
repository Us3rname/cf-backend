<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 29-12-2016
 * Time: 13:30
 */

namespace AppBundle\Service;

use AppBundle\Repository\UnitRepository;

class CreateUnitService
{
    private $unitRepository;

    public function __construct(UnitRepository $uniteRepository)
    {
        $this->unitRepository = $uniteRepository;
    }

    public function createExercise($name, $abbreviation)
    {
        return $this->unitRepository->createUnit($name, $abbreviation);
    }
}