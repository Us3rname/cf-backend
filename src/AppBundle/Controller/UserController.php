<?php

namespace AppBundle\Controller;

use AppBundle\Commands\RegisterUserCommand;
use AppBundle\Commands\UpdateUserCommand;
use AppBundle\Entity\CrossfitUser;
use AppBundle\Exception\UserAlreadyExistsException;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Model\UserModel;
use AppBundle\Repository\CrossfitUserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use League\Tactician\CommandBus;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\ConstraintViolation;

class UserController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kunnen alle users opgehaald worden.",
     *     section="User"
     * )
     *
     *
     * @QueryParam(name="limit", default="5", requirements="\d+", description="Wat is het limiet voor de response.")
     * @QueryParam(name="page", default="1", requirements="\d+", description="Van welke pagina moeten de records opgehaald worden.")
     * @Get("/user", name="read_users")
     */
    public function getUsersAction(ParamFetcher $paramFetcher): Response
    {

        /** @var CrossfitUserRepository $crossfitUserRepository */
        $crossfitUserRepository = $this->get('crossfit_user_repository');
        /** @var Paginator $users */
        $users = $crossfitUserRepository->getUsers($paramFetcher->get("limit"), $paramFetcher->get("page"));

        $response = [];
        $response['total'] = $users->count();
        $response['debug'] = [
            'limit' => $paramFetcher->get("limit"),
            'page' => $paramFetcher->get("page"),
            'offset' => $paramFetcher->get("limit") * ($paramFetcher->get("page") - 1),

        ];

        $userModel = new UserModel();
        $response['users'] = $userModel->userEntityToCFDTO($users);

        return $this->handleView($this->view($response));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kun je een specifieke user ophalen.",
     *     section="User"
     * )
     * @Get("/user/{userId}", name="read_user")
     * @param int $userId
     * @return Response Json response van de user.
     */
    public function getUserAction(int $userId): Response
    {

        /** @var CrossfitUserRepository $crossfitUserRepository */
        $crossfitUserRepository = $this->get('crossfit_user_repository');

        $userModel = new UserModel();
        $response = [];
        $response['user'] = $userModel->userEntityToCFDTO([$crossfitUserRepository->find($userId)]);
        return $this->handleView($this->view($response));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kan er een nieuwe crossfit gebruiker aangemaakt.",
     *     section="User"
     * )
     * @RequestParam(name="first_name", requirements="[a-z]+", description="Voornaam")
     * @RequestParam(name="last_name", requirements="[a-z]+", description="Achternaam")
     * @RequestParam(name="email", description="Emailadres")
     * @RequestParam(name="password", description="Password")
     * @Post("/user", name="create_user")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function createCrossfitUserAction(ParamFetcher $paramFetcher): Response
    {

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('tactician.commandbus');

        try {
            $command = new RegisterUserCommand(
                $paramFetcher->get('email'),
                $paramFetcher->get('password'),
                $paramFetcher->get('email'),
                $this->getUser()->getId()
            );
            $commandBus->handle($command);
        } catch (InvalidCommandException $invalidCommandException) {
            $violations = $invalidCommandException->getViolations();

            $errors = [];
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return $this->handleView($this->view(['result' => false, 'errors' => $errors]));
        } catch(UserAlreadyExistsException $userAlreadyExistsException) {
            return $this->handleView($this->view(['result' => false, 'errors' => [$userAlreadyExistsException->getMessage()]]));
        }


        return $this->handleView($this->view(['result' => true]));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Met deze functie kan je een crossfit gebruiker updaten.",
     *     section="User"
     * )
     * @RequestParam(name="first_name", requirements="[A-z]+", description="Voornaam")
     * @RequestParam(name="last_name", requirements="[A-z]+", description="Achternaam")
     * @RequestParam(name="email", description="Emailadres", allowBlank=true)
     * @RequestParam(name="password", description="Password", allowBlank=true)
     *
     * @Put("/user/{userId}", name="update_user")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function updateUserAction(ParamFetcher $paramFetcher, $userId) : Response
    {

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('tactician.commandbus');

        try {
            $command = new UpdateUserCommand(
                $userId,
                $paramFetcher->get('first_name'),
                $paramFetcher->get('last_name'),
                $paramFetcher->get('email')
            );

            $commandBus->handle($command);
        } catch (InvalidCommandException $invalidCommandException) {
            $violations = $invalidCommandException->getViolations();

            $errors = [];
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return $this->handleView($this->view(['result' => false, 'errors' => $errors]));
        } catch(UserNotFoundException $userAlreadyExistsException) {
            return $this->handleView($this->view(['result' => false, 'errors' => [$userAlreadyExistsException->getMessage()]]));
        }

        return $this->handleView($this->view(['result' => true]));
    }
}
