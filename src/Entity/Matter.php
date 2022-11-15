<?php

namespace App\Entity;

use App\Repository\MatterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatterRepository::class)
 */
class Matter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFirstFourMonth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $yearCarrer;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="matter")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity=Exam::class, mappedBy="matter")
     */
    private $exams;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->exams = new ArrayCollection();
    }

    public function __toString()
    {
        $cuatrimestre = ($this->isFirstFourMonth)? "Primero" : "Segundo";
        return "Materia ".$this->name." pertenece a ". $this->yearCarrer." año, cuatrimestre ".$cuatrimestre;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsFirstFourMonth(): ?bool
    {
        return $this->isFirstFourMonth;
    }

    public function setIsFirstFourMonth(?bool $isFirstFourMonth): self
    {
        $this->isFirstFourMonth = $isFirstFourMonth;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYearCarrer(): ?string
    {
        return $this->yearCarrer;
    }

    public function setYearCarrer(?string $yearCarrer): self
    {
        $this->yearCarrer = $yearCarrer;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setMatter($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getMatter() === $this) {
                $course->setMatter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exam>
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): self
    {
        if (!$this->exams->contains($exam)) {
            $this->exams[] = $exam;
            $exam->setMatter($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): self
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getMatter() === $this) {
                $exam->setMatter(null);
            }
        }

        return $this;
    }
}