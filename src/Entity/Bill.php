<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Bill
{
    const NUM_ITEMS = 20;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $printDate;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $cost;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status=0;


    /**
     * @ORM\Column(type="boolean")
     */
    private $released=1;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted=0;


    /**
     * @ORM\Column(type="integer")
     */
    private $consumption;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\WaterMeter", inversedBy="bill", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $waterMeter;

    /**
     * @ORM\Column(type="date")
     */
    private $consumptionDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="bill")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $cin;

    /**
     * @ORM\Column(type="integer")
     */
    private $previousRecord;

    /**
     * @ORM\Column(type="integer")
     */
    private $newRecord;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Receipt", inversedBy="bill", cascade={"persist", "remove"})
     */
    private $receipt;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPrintDate(): ?\DateTimeInterface
    {
        return $this->printDate;
    }

    public function setPrintDate(\DateTimeInterface $printDate): self
    {
        $this->printDate = $printDate;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReleased(): ?bool
    {
        return $this->released;
    }

    public function setReleased(bool $released): self
    {
        $this->released = $released;

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

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

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

    public function getWaterMeter(): ?WaterMeter
    {
        return $this->waterMeter;
    }

    public function setWaterMeter(WaterMeter $waterMeter): self
    {
        $this->waterMeter = $waterMeter;

        return $this;
    }

    public function getConsumptionDate(): ?\DateTimeInterface
    {
        return $this->consumptionDate;
    }

    public function setConsumptionDate(\DateTimeInterface $consumptionDate): self
    {
        $this->consumptionDate = $consumptionDate;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function __toString()
    {
        return !empty($this->id) ? $this->getConsumptionDate()->format('Y'):$this->getConsumptionDate()->format('Y') ;
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

    public function getNewRecord(): ?int
    {
        return $this->newRecord;
    }

    public function setNewRecord(int $newRecord): self
    {
        $this->newRecord = $newRecord;

        return $this;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }
}
