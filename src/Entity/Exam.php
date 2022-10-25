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

    public function __construct()
    {
        $this->inscriptionExams = new ArrayCollection();
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
}