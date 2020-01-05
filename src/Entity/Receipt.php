<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceiptRepository")
 */
class Receipt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $billNu�mber;

    /**
     * @ORM\Column(type="date")
     */
    private $billDate;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $billCost;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Bill", mappedBy="receipt", cascade={"persist", "remove"})
     */
    private $bill;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBillNu�mber(): ?string
    {
        return $this->billNu�mber;
    }

    public function setBillNu�mber(string $billNu�mber): self
    {
        $this->billNu�mber = $billNu�mber;

        return $this;
    }

    public function getBillDate(): ?\DateTimeInterface
    {
        return $this->billDate;
    }

    public function setBillDate(\DateTimeInterface $billDate): self
    {
        $this->billDate = $billDate;

        return $this;
    }

    public function getBillCost(): ?string
    {
        return $this->billCost;
    }

    public function setBillCost(string $billCost): self
    {
        $this->billCost = $billCost;

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        // set (or unset) the owning side of the relation if necessary
        $newReceipt = null === $bill ? null : $this;
        if ($bill->getReceipt() !== $newReceipt) {
            $bill->setReceipt($newReceipt);
        }

        return $this;
    }
}
