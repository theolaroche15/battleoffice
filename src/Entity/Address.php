<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $street1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street2 = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 20)]
    private ?string $zip = null;

    #[ORM\Column(length: 50)]
    private ?string $country = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'billingAddress')]
    private Collection $billingOrders;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'shippingAddress')]
    private Collection $shippingOrders;

    public function __construct()
    {
        $this->billingOrders = new ArrayCollection();
        $this->shippingOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet1(): ?string
    {
        return $this->street1;
    }

    public function setStreet1(string $street1): static
    {
        $this->street1 = $street1;

        return $this;
    }

    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    public function setStreet2(?string $street2): static
    {
        $this->street2 = $street2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getBillingOrders(): Collection
    {
        return $this->billingOrders;
    }

    public function addBillingOrder(Order $billingOrder): static
    {
        if (!$this->billingOrders->contains($billingOrder)) {
            $this->billingOrders->add($billingOrder);
            $billingOrder->setBillingAddress($this);
        }

        return $this;
    }

    public function removeBillingOrder(Order $billingOrder): static
    {
        if ($this->billingOrders->removeElement($billingOrder)) {
            // set the owning side to null (unless already changed)
            if ($billingOrder->getBillingAddress() === $this) {
                $billingOrder->setBillingAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getShippingOrders(): Collection
    {
        return $this->shippingOrders;
    }

    public function addShippingOrder(Order $shippingOrder): static
    {
        if (!$this->shippingOrders->contains($shippingOrder)) {
            $this->shippingOrders->add($shippingOrder);
            $shippingOrder->setShippingAddress($this);
        }

        return $this;
    }

    public function removeShippingOrder(Order $shippingOrder): static
    {
        if ($this->shippingOrders->removeElement($shippingOrder)) {
            // set the owning side to null (unless already changed)
            if ($shippingOrder->getShippingAddress() === $this) {
                $shippingOrder->setShippingAddress(null);
            }
        }

        return $this;
    }
}
