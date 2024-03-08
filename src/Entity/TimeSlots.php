<?php

namespace App\Entity;

use App\Repository\TimeSlotsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeSlotsRepository::class)]
class TimeSlots
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $hours = null;

    #[ORM\Column]
    private ?bool $isFree = null;

    #[ORM\ManyToMany(targetEntity: WorkingDay::class, mappedBy: 'timeSlots')]
    private Collection $workingDays;

    public function __construct()
    {
        $this->workingDays = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getHours();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHours(): ?string
    {
        return $this->hours;
    }

    public function setHours(string $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function isIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(bool $isFree): static
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * @return Collection<int, WorkingDay>
     */
    public function getWorkingDays(): Collection
    {
        return $this->workingDays;
    }

    public function addWorkingDay(WorkingDay $workingDay): static
    {
        if (!$this->workingDays->contains($workingDay)) {
            $this->workingDays->add($workingDay);
            $workingDay->addTimeSlot($this);
        }

        return $this;
    }

    public function removeWorkingDay(WorkingDay $workingDay): static
    {
        if ($this->workingDays->removeElement($workingDay)) {
            $workingDay->removeTimeSlot($this);
        }

        return $this;
    }
    
}
