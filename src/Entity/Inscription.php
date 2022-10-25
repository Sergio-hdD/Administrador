<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 * @ORM\Table(name="inscription")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "inscriptionExam" = "InscriptionExam",
 *      "inscriptionCourse" = "InscriptionCourse"
 * })
 */
abstract class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $timeSince;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $timeUntil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeSince(): ?\DateTimeInterface
    {
        return $this->timeSince;
    }

    public function setTimeSince(?\DateTimeInterface $timeSince): self
    {
        $this->timeSince = $timeSince;

        return $this;
    }

    public function getTimeUntil(): ?\DateTimeInterface
    {
        return $this->timeUntil;
    }

    public function setTimeUntil(?\DateTimeInterface $timeUntil): self
    {
        $this->timeUntil = $timeUntil;

        return $this;
    }
}
