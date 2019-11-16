<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $streetAddress;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $houseNumber;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $district;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipCode;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Client", inversedBy="address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\WaterMeter", inversedBy="address", cascade={"persist", "remove"})
     */
    private $waterMeter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(string $streetAddress): self
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getHouseNumber(): ?int
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?int $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function __toString(): string
    {
        $houseNum = !empty($this->houseNumber)?'nr '. $this->houseNumber:'';
        return $this->streetAddress . ' '. $houseNum .' '.$this->city.' '.$this->district.' '.$this->zipCode;
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


}
