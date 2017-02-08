<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CrossfitUserRepository")
 * @ORM\Table(name="crossfit_user")
 */
class CrossfitUser extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Profile
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Profile", mappedBy="user", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="profile", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $profile;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->profile;
    }
}