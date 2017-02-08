<?php

namespace AppBundle\Controller;

use AppBundle\Commands\LogResultCommand;
use AppBundle\Exception\ExerciseNotFoundException;
use AppBundle\Exception\UnitNotFoundException;
use AppBundle\Exception\UserNotFoundException;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use League\Tactician\CommandBus;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class LogResultController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kan er een nieuwe oefening aangemaakt worden.",
     *     section="Exercise"
     * )
     * @RequestParam(name="weight", description="Naam van de exercise.")
     * @RequestParam(name="unitId", description="Naam van de exercise.")
     * @Post("/exercise/{exerciseId}/result", name="create_result")
     */
    public function createResultAction(int $exerciseId, ParamFetcher $paramFetcher) : Response
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->get('tactician.commandbus');

        $logResultCommand = new LogResultCommand(
            $this->getUser()->getId(),
            $exerciseId,
            $paramFetcher->get('unitId'),
            $paramFetcher->get('weight'),
            $this->getUser()
        );

        $errorMessage = '';
        try {
            $commandBus->handle($logResultCommand);
        } catch (UserNotFoundException $userNotFoundException) {
            $errorMessage = $userNotFoundException->getMessage();
        } catch (ExerciseNotFoundException $exerciseNotFoundException) {
            $errorMessage = $exerciseNotFoundException->getMessage();
        } catch (UnitNotFoundException $unitNotFoundException) {
            $errorMessage = $unitNotFoundException->getMessage();
        }

        return $this->handleView($this->view(['result' => 'ok', 'error' => $errorMessage]));
    }
}
