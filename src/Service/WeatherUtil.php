<?php

namespace App\Service;

use App\Entity\Location;
use App\Entity\WeatherHistory;
use App\Repository\LocationRepository;
use App\Repository\WeatherHistoryRepository;

class WeatherUtil
{
    private WeatherHistoryRepository $weatherHistoryRepository;
    private LocationRepository $locationRepository;

    public function __construct(
        WeatherHistoryRepository $weatherHistoryRepository,
        LocationRepository $locationRepository
    ) {
        $this->weatherHistoryRepository = $weatherHistoryRepository;
        $this->locationRepository       = $locationRepository;
    }

    /**
     * @return WeatherHistory[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->weatherHistoryRepository->findBy(['location' => $location]);
    }

    /**
     * @return WeatherHistory[]
     */
    public function getWeatherForCountryAndCity(string $country, string $city): array
    {
        $location = $this->locationRepository->findOneBy(['country' => $country, 'city' => $city]);

        if (!$location) {
            return [];
        }

        return $this->weatherHistoryRepository->findBy(['location' => $location]);
    }
}