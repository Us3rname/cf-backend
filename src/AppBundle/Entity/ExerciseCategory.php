<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 7-1-2017
 * Time: 18:26
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Exercise
 *
 * @ORM\Table(name="exercise_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExerciseCategoryRepository")
 */
class ExerciseCategory extends GenericEntity
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="CrossfitUser")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ExerciseCategory
     */
    public function setName(string $name): ExerciseCategory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return CrossfitUser
     */
    public function getCreatedBy(): CrossfitUser
    {
        return $this->createdBy;
    }

    /**
     * @param CrossfitUser $createdBy
     */
    public function setCreatedBy(CrossfitUser $createdBy)
    {
        $this->createdBy = $createdBy;
    }

}
