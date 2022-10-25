<?php

namespace App\Entity;

use App\Repository\StudentCourseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentCourseRepository::class)
 */
class StudentCourse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="studentCourses")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="studentCourses")
     */
    private $student;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $noteFirst;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $noteSecond;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getNoteFirst(): ?int
    {
        return $this->noteFirst;
    }

    public function setNoteFirst(int $noteFirst): self
    {
        $this->noteFirst = $noteFirst;

        return $this;
    }

    public function getNoteSecond(): ?int
    {
        return $this->noteSecond;
    }

    public function setNoteSecond(?int $noteSecond): self
    {
        $this->noteSecond = $noteSecond;

        return $this;
    }
}
