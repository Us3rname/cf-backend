<?php

namespace AppBundle\Controller;

use AppBundle\Commands\AddExerciseCommand;
use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
use AppBundle\Service\CreateExercise;
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

class ExerciseController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kunnen de verschillende oefeningen opgehaald worden.",
     *     section="Excercise"
     * )
     *
     * @Get("/exercise", name="read_exercise")
     * @return Response
     */
    public function getExercisesAction(): Response
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
     *     section="Exercise"
     * )
     * @Get("/exercise/{exerciseId}", name="read_exercise")
     * @param int $exerciseId
     * @return Response Json response van de unit.
     */
    public function getExerciseAction(int $exerciseId): Response
    {

        /** @var ExerciseRepository $exerciseRepo */
        $exerciseRepo = $this->get('exercise_repository');

        return $this->handleView($this->view($exerciseRepo->find($exerciseId)));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kan er een nieuwe oefening aangemaakt worden.",
     *     section="Exercise"
     * )
     * @RequestParam(name="nameExercise",  description="Naam van de exercise.")
     * @RequestParam(name="exerciseCategoryId", description="Naam van de exercise.")
     * @Post("/exercise", name="create_exercise")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function createExerciseAction(ParamFetcher $paramFetcher) : Response
    {

        $addExerciseCommand = new AddExerciseCommand(
            $paramFetcher->get('nameExercise'),
            (int) $paramFetcher->get('exerciseCategoryId'),
            $this->getUser()->getId()
        );

        try {
            /** @var CommandBus $commandBus */
            $commandBus = $this->get('tactician.commandbus');
            $commandBus->handle($addExerciseCommand);
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

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kan er een nieuwe oefening aangemaakt worden.",
     *     section="Exercise"
     * )
     * @RequestParam(name="exercise_name", requirements="[a-z]+", description="Naam van de exercise.")
     * @Put("/exercise", name="update_exercise")
     */
    public function updateExerciseAction(ParamFetcher $paramFetcher) : Response
    {
        return $this->handleView($this->view(['bla' => $paramFetcher->get('name_exercise')]));
    }
}
