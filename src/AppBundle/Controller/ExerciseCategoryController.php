<?php

namespace AppBundle\Controller;

use AppBundle\Commands\AddExerciseCategoryCommand;
use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
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

class ExerciseCategoryController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kunnen de verschillende oefeningen opgehaald worden.",
     *     section="ExcerciseCategory"
     * )
     *
     * @Get("/exercisecategory", name="read_exercise_categories")
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
     *  description="Met deze functie kun je een specifieke oefening ophalen.",
     *     section="ExerciseCategory"
     * )
     * @Get("/exercisecategory/{exerciseCategoryId}", name="read_exercise_category")
     * @param int $exerciseId
     * @return Response Json response van de unit.
     */
    public function getExerciseCategoryAction(int $exerciseId): Response
    {

        /** @var ExerciseRepository $exerciseRepo */
        $exerciseRepo = $this->get('exercise_repository');

        return $this->handleView($this->view($exerciseRepo->find($exerciseId)));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kan er een nieuwe oefening aangemaakt worden.",
     *     section="ExerciseCategory"
     * )
     * @RequestParam(name="nameExerciseCategory", description="Naam van de exercise categorie.")
     * @Post("/exercisecategory", name="create_exercise_category")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function createExerciseCategoryAction(ParamFetcher $paramFetcher): Response
    {

        $addExerciseCategoryCommand = new AddExerciseCategoryCommand(
            $paramFetcher->get('nameExerciseCategory'), $this->getUser()->getId()
        );

        try {
            /** @var CommandBus $commandBus */
            $commandBus = $this->get('tactician.commandbus');
            $commandBus->handle($addExerciseCategoryCommand);
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
