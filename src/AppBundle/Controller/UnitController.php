<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Unit;
use AppBundle\Repository\UnitRepository;
use AppBundle\Service\CreateUnitService;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UnitController
 * @package AppBundle\Controller
 */
class UnitController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kunnen de verschillende units opgehaald worden.",
     *     section="Unit"
     * )
     *
     * @Get("/unit", name="read_unit")
     */
    public function getUnitsAction(): Response
    {

        /** @var UnitRepository $unitRepo */
        $unitRepo = $this->get('unit_repository');
        $units = $unitRepo->findAll();

        $response = [];
        /** @var Unit $unit */
        foreach ($units as $unit) {
            $response[$unit->getId()] = $unit;

        }

        return $this->handleView($this->view($response));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kun je een specifieke unit ophalen.",
     *     section="Unit"
     * )
     * @Get("/unit/{unitId}", name="read_unit")
     * @param int $unitId
     * @return Response Json response van de unit.
     */
    public function getUnitAction(int $unitId): Response
    {

        /** @var UnitRepository $unitRepo */
        $unitRepo = $this->get('unit_repository');

        return $this->handleView($this->view($unitRepo->find($unitId)));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kan er een nieuwe unit aangemaakt worden.",
     *     section="Unit"
     * )
     * @RequestParam(name="unit_name", requirements="[a-z]+", description="Naam van de unit (kg/lbs).")
     * @RequestParam(name="unit_abbreviation", requirements="[a-z]+", description="Naam van de unit (kilogram/pounds).")
     * @Post("/unit", name="create_unit")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response Json response met het id.
     */
    public function createUnitAction(ParamFetcher $paramFetcher): Response
    {

        // Todo omzetten naar command?
        /** @var CreateUnitService $unitService */
        $unitService = $this->get('unit_service');

        /** @var Unit $unit */
        $unit = $unitService->createExercise($paramFetcher->get('unit_name'), $paramFetcher->get('unit_abbreviation'));
        return $this->handleView($this->view(['id' => $unit->getId()]));
    }

}
