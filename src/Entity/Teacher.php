<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher extends User
{
    const STR_USER_TYPE = "teacher";
    /**
     * @ORM\ManyToMany(targetEntity=Course::class, inversedBy="teachers")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=TeacherExam::class, mappedBy="teacher")
     */
    private $teacherExams;

    public function __construct()
    {
        $this->course = new ArrayCollection();
        $this->teacherExams = new ArrayCollection();
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourse(): Collection
    {
        return $this->course;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->course->contains($course)) {
            $this->course[] = $course;
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        $this->course->removeElement($course);

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
            $teacherExam->setTeacher($this);
        }

        return $this;
    }

    public function removeTeacherExam(TeacherExam $teacherExam): self
    {
        if ($this->teacherExams->removeElement($teacherExam)) {
            // set the owning side to null (unless already changed)
            if ($teacherExam->getTeacher() === $this) {
                $teacherExam->setTeacher(null);
            }
        }

        return $this;
    }
}
