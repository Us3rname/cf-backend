<?php

namespace AppBundle\EventListener;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;

class OAuthListener
{

    /** @var  ClientManagerInterface $clientManager */
    private $clientManager;

    /**
     * UserRightsListener constructor.
     * @param $clientManager
     */
    public function __construct(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * Create oauth login for the user.
     */
    public function onUserRegistered()
    {
        $client = $this->clientManager->createClient();
        $client->setAllowedGrantTypes(array('token', 'authorization_code', ['password']));
        $this->clientManager->updateClient($client);
    }
}