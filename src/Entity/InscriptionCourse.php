<?php

namespace App\Entity;

use App\Repository\InscriptionCourseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionCourseRepository::class)
 */
class InscriptionCourse extends Inscription
{
    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="inscriptionCourses")
     */
    private $course;

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }
}
