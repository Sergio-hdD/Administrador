<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Matter::class, inversedBy="courses")
     */
    private $matter;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $untilChangeNote;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateStart;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $dayCourse;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $turn;

    /**
     * @ORM\OneToMany(targetEntity=InscriptionCourse::class, mappedBy="course")
     */
    private $inscriptionCourses;

    /**
     * @ORM\ManyToMany(targetEntity=Teacher::class, mappedBy="course")
     */
    private $teachers;

    /**
     * @ORM\OneToMany(targetEntity=StudentCourse::class, mappedBy="course")
     */
    private $studentCourses;

    public function __construct()
    {
        $this->inscriptionCourses = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->studentCourses = new ArrayCollection();
    }

    public function __toString()
    {
        return "Cursada de materia ".$this->matter->getName()." dia ".$this->dayCourse." turno ".$this->turn." inicio ".$this->dateStart->format('d-m-Y')." fin ".$this->dateEnd->format('d-m-Y');    
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatter(): ?Matter
    {
        return $this->matter;
    }

    public function setMatter(?Matter $matter): self
    {
        $this->matter = $matter;

        return $this;
    }

    public function getUntilChangeNote(): ?\DateTimeInterface
    {
        return $this->untilChangeNote;
    }

    public function setUntilChangeNote(?\DateTimeInterface $untilChangeNote): self
    {
        $this->untilChangeNote = $untilChangeNote;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getDayCourse(): ?string
    {
        return $this->dayCourse;
    }

    public function setDayCourse(?string $dayCourse): self
    {
        $this->dayCourse = $dayCourse;

        return $this;
    }

    public function getTurn(): ?string
    {
        return $this->turn;
    }

    public function setTurn(?string $turn): self
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * @return Collection<int, InscriptionCourse>
     */
    public function getInscriptionCourses(): Collection
    {
        return $this->inscriptionCourses;
    }

    public function addInscriptionCourse(InscriptionCourse $inscriptionCourse): self
    {
        if (!$this->inscriptionCourses->contains($inscriptionCourse)) {
            $this->inscriptionCourses[] = $inscriptionCourse;
            $inscriptionCourse->setCourse($this);
        }

        return $this;
    }

    public function removeInscriptionCourse(InscriptionCourse $inscriptionCourse): self
    {
        if ($this->inscriptionCourses->removeElement($inscriptionCourse)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionCourse->getCourse() === $this) {
                $inscriptionCourse->setCourse(null);
            }
        }

        return $this;
    }    

    /**
     * @return Collection<int, Teacher>
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $teacher): self
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers[] = $teacher;
            $teacher->addCourse($this);
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): self
    {
        if ($this->teachers->removeElement($teacher)) {
            $teacher->removeCourse($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentCourse>
     */
    public function getStudentCourses(): Collection
    {
        return $this->studentCourses;
    }

    public function addStudentCourse(StudentCourse $studentCourse): self
    {
        if (!$this->studentCourses->contains($studentCourse)) {
            $this->studentCourses[] = $studentCourse;
            $studentCourse->setCourse($this);
        }

        return $this;
    }

    public function removeStudentCourse(StudentCourse $studentCourse): self
    {
        if ($this->studentCourses->removeElement($studentCourse)) {
            // set the owning side to null (unless already changed)
            if ($studentCourse->getCourse() === $this) {
                $studentCourse->setCourse(null);
            }
        }

        return $this;
    }
}