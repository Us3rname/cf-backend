<?php

namespace AppBundle\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;

class UserModel
{

    public function userEntityToCFDTO($cfUsers) : array
    {

        $usersDTO = [];
        /** @var \AppBundle\Entity\CrossfitUser $cfUser */
        foreach ($cfUsers as $cfUser) {

            $cfDTO = new \AppBundle\DTO\CrossfitUser(
                $cfUser->getId(),
                $cfUser->getProfile()->getFirstname(),
                $cfUser->getProfile()->getLastname(),
                $cfUser->getEmail()
            );
            $usersDTO[] = $cfDTO;
        }

        return $usersDTO;
    }
}