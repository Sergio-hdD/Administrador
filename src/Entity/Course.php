<?php

namespace App\Entity;

use App\Repository\CourseRepository;
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
}