<?php

namespace AppBundle\Controller;

use AppBundle\Commands\AddAffiliateCommand;
use AppBundle\Commands\AddExerciseCategoryCommand;
use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
use AppBundle\ValueObjects\ContactDetails;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use League\Tactician\CommandBus;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;

class AffiliateController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="",
     *     section="Affiliate"
     * )
     *
     * @Get("/affiliate", name="read_all_affiliate")
     * @return Response
     */
    public function getExerciseCategoriesAction(): Response
    {

        /** @var ExerciseRepository $exerciseRepo */
        $exerciseRepo = $this->get('exercise_repository');
        $exercises = $exerciseRepo->findAll();

        $response = [];
        /** @var Exercise $exercise */
        foreach ($exercises as $exercise) {
            $response[$exercise->getId()] = $exercise;

        }

        return $this->handleView($this->view($response));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="",
     *     section="Affiliate"
     * )
     * @Get("/affiliate/{affiliateId}", name="read_affiliate")
     * @param int $affiliateId
     * @return Response Json response van de unit.
     */
    public function getExerciseCategoryAction(int $affiliateId): Response
    {

        /** @var ExerciseRepository $exerciseRepo */
        $exerciseRepo = $this->get('exercise_repository');

        return $this->handleView($this->view($exerciseRepo->find($affiliateId)));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="CF box toevoegen.",
     *     section="Affiliate"
     * )
     * @RequestParam(name="name", description="Naam van de crossfit box.")
     * @RequestParam(name="street", description="Straatnaam")
     * @RequestParam(name="house_number", description="Huisnummer.")
     * @RequestParam(name="house_number_addition", description="Toevoeging op het huisnummer.")
     * @RequestParam(name="postal_code", description="Postcode.")
     * @RequestParam(name="city", description="Naam van de exercise categorie.")
     * @RequestParam(name="country", description="Naam van de exercise categorie.")
     * @RequestParam(name="emailadres", description="Naam van de exercise categorie.")
     * @RequestParam(name="phone_number", description="Naam van de exercise categorie.")
     *
     * @Post("/affiliate", name="create_affiliate")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function createExerciseCategoryAction(ParamFetcher $paramFetcher): Response
    {

        $contactDetails = new ContactDetails(
            $paramFetcher->get('street'),
            $paramFetcher->get('house_number'),
            $paramFetcher->get('house_number_addition'),
            $paramFetcher->get('postal_code'),
            $paramFetcher->get('city'),
            $paramFetcher->get('country'),
            $paramFetcher->get('emailadres'),
            $paramFetcher->get('phone_number')
            );

        $addAffiliateCommand = new AddAffiliateCommand(
            $this->getUser()->getId(),
            $paramFetcher->get('name'),
            $contactDetails
        );

        try {
            /** @var CommandBus $commandBus */
            $commandBus = $this->get('tactician.commandbus');
            $commandBus->handle($addAffiliateCommand);
        } catch (InvalidCommandException $invalidCommandException) {
            $violations = $invalidCommandException->getViolations();

            $errors = [];
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return $this->handleView($this->view(['result' => false, 'errors' => $errors]));
        } catch (\Exception $exception) {
            return $this->handleView($this->view(['result' => false, 'error' => $exception->getMessage()]));
        }

        return $this->handleView($this->view(['result' => true]));
    }
}
