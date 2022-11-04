<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExamRepository::class)
 */
class Exam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Matter::class, inversedBy="exams")
     */
    private $matter;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $hora;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $untilChangeNote;

    /**
     * @ORM\OneToMany(targetEntity=InscriptionExam::class, mappedBy="exam")
     */
    private $inscriptionExams;

    /**
     * @ORM\OneToMany(targetEntity=TeacherExam::class, mappedBy="exam")
     */
    private $teacherExams;

    /**
     * @ORM\OneToMany(targetEntity=StudentExam::class, mappedBy="exam")
     */
    private $studentExams;

    public function __construct()
    {
        $this->inscriptionExams = new ArrayCollection();
        $this->teacherExams = new ArrayCollection();
        $this->studentExams = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->matter." fecha ".$this->fecha->format('d-m-Y')." hora ".$this->hora->format('HH:mm');
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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(?\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

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

    /**
     * @return Collection<int, InscriptionExam>
     */
    public function getInscriptionExams(): Collection
    {
        return $this->inscriptionExams;
    }

    public function addInscriptionExam(InscriptionExam $inscriptionExam): self
    {
        if (!$this->inscriptionExams->contains($inscriptionExam)) {
            $this->inscriptionExams[] = $inscriptionExam;
            $inscriptionExam->setExam($this);
        }

        return $this;
    }

    public function removeInscriptionExam(InscriptionExam $inscriptionExam): self
    {
        if ($this->inscriptionExams->removeElement($inscriptionExam)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionExam->getExam() === $this) {
                $inscriptionExam->setExam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeacherExam>
     */
    public function getTeacherExams(): Collection
    {
        return $this->teacherExams;
    }

    public function addTeacherExam(TeacherExam $teacherExam): self
    {
        if (!$this->teacherExams->contains($teacherExam)) {
            $this->teacherExams[] = $teacherExam;
            $teacherExam->setExam($this);
        }

        return $this;
    }

    public function removeTeacherExam(TeacherExam $teacherExam): self
    {
        if ($this->teacherExams->removeElement($teacherExam)) {
            // set the owning side to null (unless already changed)
            if ($teacherExam->getExam() === $this) {
                $teacherExam->setExam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentExam>
     */
    public function getStudentExams(): Collection
    {
        return $this->studentExams;
    }

    public function addStudentExam(StudentExam $studentExam): self
    {
        if (!$this->studentExams->contains($studentExam)) {
            $this->studentExams[] = $studentExam;
            $studentExam->setExam($this);
        }

        return $this;
    }

    public function removeStudentExam(StudentExam $studentExam): self
    {
        if ($this->studentExams->removeElement($studentExam)) {
            // set the owning side to null (unless already changed)
            if ($studentExam->getExam() === $this) {
                $studentExam->setExam(null);
            }
        }

        return $this;
    }
}