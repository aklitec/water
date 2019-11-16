<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class Client
{
    const NUM_ITEMS = 10;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $cin;

/*    /**
     * @ORM\Column(type="string", length=255)
     */
 //   private $address;

    /**
     * @ORM\Column(type="float")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string" , length=50)
     */
    private $fullName;

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
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;



    /**
     * @ORM\Column(name="createdAt", type="datetime")
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(name="deleted" , type="boolean")
     * @var boolean
     */
    private $deleted = 0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", mappedBy="client", cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WaterMeter", mappedBy="client", orphanRemoval=true)
     */
    private $waterMeters;

    public function __construct()
    {
        $this->waterMeters = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

   /* public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }*/

    public function getPhoneNumber(): ?float
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(float $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onPrePersist(){
        $fullName = $this->getFirstName().' '.$this->getLastName();
        $this->setFullName($fullName);
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        // set the owning side of the relation if necessary
        if ($this !== $address->getClient()) {
            $address->setClient($this);
        }

        return $this;
    }

    /**
     * @return Collection|WaterMeter[]
     */
    public function getWaterMeters(): Collection
    {
        return $this->waterMeters;
    }

    public function addWaterMeter(WaterMeter $waterMeter): self
    {
        if (!$this->waterMeters->contains($waterMeter)) {
            $this->waterMeters[] = $waterMeter;
            $waterMeter->setClient($this);
        }

        return $this;
    }

    public function removeWaterMeter(WaterMeter $waterMeter): self
    {
        if ($this->waterMeters->contains($waterMeter)) {
            $this->waterMeters->removeElement($waterMeter);
            // set the owning side to null (unless already changed)
            if ($waterMeter->getClient() === $this) {
                $waterMeter->setClient(null);
            }
        }

        return $this;
    }




}
