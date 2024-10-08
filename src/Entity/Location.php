<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $longitude = null;

    /**
     * @var Collection<int, WeatherHistory>
     */
    #[ORM\OneToMany(targetEntity: WeatherHistory::class, mappedBy: 'location_id')]
    private Collection $weatherHistories;

    public function __construct()
    {
        $this->weatherHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, WeatherHistory>
     */
    public function getWeatherHistories(): Collection
    {
        return $this->weatherHistories;
    }

    public function addWeatherHistory(WeatherHistory $weatherHistory): static
    {
        if (!$this->weatherHistories->contains($weatherHistory)) {
            $this->weatherHistories->add($weatherHistory);
            $weatherHistory->setLocationId($this);
        }

        return $this;
    }

    public function removeWeatherHistory(WeatherHistory $weatherHistory): static
    {
        if ($this->weatherHistories->removeElement($weatherHistory)) {
            // set the owning side to null (unless already changed)
            if ($weatherHistory->getLocationId() === $this) {
                $weatherHistory->setLocationId(null);
            }
        }

        return $this;
    }
}
