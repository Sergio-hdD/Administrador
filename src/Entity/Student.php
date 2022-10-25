<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student extends User
{
    /**
     * @ORM\OneToMany(targetEntity=StudentExam::class, mappedBy="student")
     */
    private $studentExams;

    public function __construct()
    {
        $this->studentExams = new ArrayCollection();
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
            $studentExam->setStudent($this);
        }

        return $this;
    }

    public function removeStudentExam(StudentExam $studentExam): self
    {
        if ($this->studentExams->removeElement($studentExam)) {
            // set the owning side to null (unless already changed)
            if ($studentExam->getStudent() === $this) {
                $studentExam->setStudent(null);
            }
        }

        return $this;
    }
}
