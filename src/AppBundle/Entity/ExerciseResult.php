<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exercise
 *
 * @ORM\Table(name="exercise_result")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExerciseResultRepository")
 */
class ExerciseResult
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CrossfitUser")
     * @var CrossfitUser
     */
    protected $crossfitUser;

    /**
     * @ORM\ManyToOne(targetEntity="Unit")
     * @var Unit
     */
    protected $unit;

    /**
     * @ORM\ManyToOne(targetEntity="Exercise")
     * @var Exercise
     */
    protected $exercise;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $result;

    /**
     * ExerciseResult constructor.
     * @param $crossfitUser
     * @param $unit
     * @param $exercise
     */
    public function __construct(CrossfitUser $crossfitUser, Unit $unit, Exercise $exercise, float $result)
    {
        $this->crossfitUser = $crossfitUser;
        $this->unit = $unit;
        $this->exercise = $exercise;
        $this->result = $result;
    }

}