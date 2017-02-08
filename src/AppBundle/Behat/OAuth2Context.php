<?php

namespace AppBundle\Behat;

use AppBundle\Entity\Client as OAuthClient;
use AppBundle\Entity\CrossfitUser;
use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\OAuthServerBundle\Model\TokenInterface;
use FOS\OAuthServerBundle\Model\TokenManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Gorghoa\ScenarioStateBehatExtension\Context\ScenarioStateAwareContext;
use Gorghoa\ScenarioStateBehatExtension\ScenarioStateInterface;

class OAuth2Context extends RawMinkContext implements Context, ScenarioStateAwareContext
{
    /**
     * @var ScenarioStateInterface
     */
    private $scenarioState;

    /**
     * @var UserManagerInterface
     */
    private $userManager;
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;
    /**
     * @var TokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var TokenInterface
     */
    protected $token;

    public function __construct(
        UserManagerInterface $userManager,
        ClientManagerInterface $clientManager,
        TokenManagerInterface $tokenManager
    )
    {
        $this->userManager = $userManager;
        $this->clientManager = $clientManager;
        $this->tokenManager = $tokenManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setScenarioState(ScenarioStateInterface $scenarioState)
    {
        $this->scenarioState = $scenarioState;
    }

    /**
     * @Given /^I am authenticated as (user|admin)$/
     *
     * @param string $userOrAdmin
     */
    public function iAmAuthenticatedAs($userOrAdmin)
    {
        $username = 'user' === $userOrAdmin ? 'alice' : 'admin';
        $clientId = 'user' === $userOrAdmin ? -2 : -1;

        /** @var CrossfitUser $user */
        if (null === ($user = $this->userManager->findUserBy(['username' => $username]))) {
            $user = $this->userManager->createUser();
            $user->setUsername($username);
            $user->setPlainPassword('password');
            $user->setEmail($username . '@example.com');
            $this->userManager->updateUser($user);
        }
        /** @var OAuthClient $client */
        if (null === ($client = $this->clientManager->findClientBy(['id' => $clientId]))) {
            $client = $this->clientManager->createClient();
            $this->clientManager->updateClient($client);
        }

        // Altijd maar even een token aanmaken.
        $this->token = $this->tokenManager->createToken();
        $this->token->setUser($user);
        $this->token->setClient($client);
        $this->token->setToken(md5(uniqid()));
        $this->tokenManager->updateToken($this->token);
        $this->scenarioState->provideStateFragment('token', $this->token);
    }
}