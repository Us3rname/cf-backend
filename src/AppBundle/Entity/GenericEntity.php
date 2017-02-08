<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class GenericEntity
{
    /**
     * @ORM\Column(type="datetime", name="created")
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", name="updated")
     * @var \DateTime
     */
    protected $updated;


    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist()
     */
    public function executeOnPrePersist()
    {
        $this->updated = new \DateTime();
        $this->created = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function executeOnPreUpdate()
    {
        $this->updated = new \DateTime();
    }
}