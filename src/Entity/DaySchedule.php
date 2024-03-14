<?php

namespace App\Entity;

use App\Repository\DayScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DayScheduleRepository::class)]
class DaySchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $dayOfWeek = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeInterval = null;

    #[ORM\Column]
    private ?bool $isWork = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
        return $this->getDayOfWeek();
    }

    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTimeInterval(): ?int
    {
        return $this->timeInterval;
    }

    public function setTimeInterval(?int $timeInterval): static
    {
        $this->timeInterval = $timeInterval;

        return $this;
    }

    public function isIsWork(): ?bool
    {
        return $this->isWork;
    }

    public function setIsWork(bool $isWork): static
    {
        $this->isWork = $isWork;

        return $this;
    }
}
