<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @Vich\Uploadable
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="client_image", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addresse;

    /**
     * @ORM\Column(type="float")
     */
    private $counterNumber;

    /**
     * @ORM\Column(type="datetime" ,nullable=false)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if (null!== $image)  {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(?string $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getCounterNumber(): ?float
    {
        return $this->counterNumber;
    }

    public function setCounterNumber(float $counterNumber): self
    {
        $this->counterNumber = $counterNumber;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new \DateTime('now');
        $this->setUpdatedAt($dateTimeNow);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);

        }
    }
}
