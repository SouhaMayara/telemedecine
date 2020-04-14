<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialityRepository")
 */
class Speciality
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Doctor", mappedBy="speciality")
     */
    private $doctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDoctor()
    {
        return $this->doctor;
    }

    public function setDoctor($doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }
    public function __toString(){
        return ($this->name);
    }
}
