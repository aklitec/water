<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsumptionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Consumption
{
    const NUM_ITEMS = 12 ;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $previousRecord=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentRecord=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $consumption;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $cost;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status=0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WaterMeter", inversedBy="consumptions")
     */
    private $waterMeter;

    /**
     * @ORM\Column(type="smallint")
     */
    private $costPerMeterCube;

    /**
     * @ORM\Column(type="smallint")
     */
    private $month;

    /**
     * @ORM\Column(name="deleted" , type="boolean")
     * @var boolean
     */
    private $deleted = 0;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {

        $this->date = $date;
        $month = (int)date('m',strtotime($date->format('F')));
        $this->setMonth($month);
        return $this;
    }

    public function getPreviousRecord(): ?int
    {
        return $this->previousRecord;
    }

    public function setPreviousRecord(int $previousRecord): self
    {
        $this->previousRecord = $previousRecord;

        return $this;
    }

    public function getCurrentRecord(): ?int
    {
        return $this->currentRecord;
    }

    public function setCurrentRecord(int $currentRecord): self
    {
        $this->currentRecord = $currentRecord;

        return $this;
    }

    public function getConsumption(): ?int
    {
        return $this->consumption;
    }

    public function setConsumption(int $consumption): self
    {
        $this->consumption = $consumption;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getWaterMeter(): ?WaterMeter
    {
        return $this->waterMeter;
    }

    public function setWaterMeter(?WaterMeter $waterMeter): self
    {
        $this->waterMeter = $waterMeter;

        return $this;
    }
    public function __construct()
    {
        $this->code = Uuid::uuid4();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function getCostPerMeterCube(): ?int
    {
        return $this->costPerMeterCube;
    }

    public function setCostPerMeterCube(int $costPerMeterCube): self
    {
        $this->costPerMeterCube = $costPerMeterCube;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }


    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }
    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function __toString()
        {
        return !empty($this->id) ? $this->getDate()->format('Y') : $this->getDate()->format('Y' ) ;
    }





}
