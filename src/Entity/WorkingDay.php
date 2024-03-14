<?php

namespace App\Entity;

use App\Repository\WorkingDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: WorkingDayRepository::class)]
class WorkingDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $dayOfWeek = null;

    #[ORM\ManyToMany(targetEntity: TimeSlots::class, inversedBy: 'workingDays')]
    private Collection $timeSlots;

    #[ORM\Column]
    private ?bool $available = null;


    public function __construct()
    {
        $this->timeSlots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(string $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * @return Collection<int, TimeSlots>
     */
    public function getTimeSlots(): Collection
    {
        return $this->timeSlots;
    }

    public function addTimeSlot(TimeSlots $timeSlot): static
    {
        if (!$this->timeSlots->contains($timeSlot)) {
            $this->timeSlots->add($timeSlot);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlots $timeSlot): static
    {
        $this->timeSlots->removeElement($timeSlot);

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }
}
