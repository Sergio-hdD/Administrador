<?php

namespace App\Entity;

use App\Repository\InscriptionExamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionExamRepository::class)
 */
class InscriptionExam extends Inscription
{
    /**
     * @ORM\ManyToOne(targetEntity=Exam::class, inversedBy="inscriptionExams")
     */
    private $exam;

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): self
    {
        $this->exam = $exam;

        return $this;
    }
}
